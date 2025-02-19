<?php

namespace app\controllers;

use Yii;
use app\models\ProductImages;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\HttpBearerAuth;

class ProductImagesController extends ActiveController
{
    public $modelClass = 'app\models\ProductImages';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['verbs']);

        // Authentication via Bearer Token
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        // Access control
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['@'], // Authorized users
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->getRole() === 'admin'; // Role check
                    },
                ],
            ],
            'denyCallback' => function () {
                throw new ForbiddenHttpException('You do not have sufficient rights to perform this action.');
            },
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']); // Удаляем базовые действия, чтобы переопределить их
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider($action)
    {
        $query = $this->modelClass::find();
        $productId = Yii::$app->request->get('product_id');

        if ($productId !== null) {
            $query->andWhere(['product_id' => $productId]);
            $query->orderBy(['sort_order' => SORT_ASC]); // Исправлено: используем sort_order
        }

        return new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);
    }

    public function actionCreate()
{
    $model = new $this->modelClass();
    $model->load(Yii::$app->getRequest()->getBodyParams(), '');

    $imageFiles = UploadedFile::getInstancesByName('imageFiles'); // Множественная загрузка
    $isMain = Yii::$app->request->post('is_main', null); // Получаем значение is_main

    $transaction = Yii::$app->db->beginTransaction();
    try {
        if ($imageFiles) {
            // Получаем максимальный sort_order для данного product_id
            $maxSortOrder = ProductImages::find()
                ->where(['product_id' => $model->product_id])
                ->max('sort_order');
            $nextSortOrder = $maxSortOrder === null ? 1 : $maxSortOrder + 1;

            foreach ($imageFiles as $index => $imageFile) {
                if (!$this->validateImageFile($imageFile)) {
                    throw new UnprocessableEntityHttpException('Invalid image file.');
                }

                try {
                    $filePath = $this->saveImageFile($imageFile);

                    $image = new $this->modelClass();
                    $image->product_id = $model->product_id; // Ensure product_id is set
                    $image->url = $filePath;
                    $image->is_main = ($isMain !== null && $isMain == $index) ? 1 : 0; // Сравниваем индекс с is_main
                    $image->sort_order = Yii::$app->request->post('sort_order')[$index] ?? $nextSortOrder++; // Get sort_order for each image, or assign the next available

                    if (!$image->save()) {
                        throw new UnprocessableEntityHttpException(json_encode($image->errors));
                    }
                } catch (\Exception $e) {
                    throw new ServerErrorHttpException('Failed to save image file: ' . $e->getMessage());
                }
            }

            // Вызываем resetMainImage только если is_main был передан
            if ($isMain !== null) {
                $this->resetMainImage($model->product_id, $isMain);
            }
        }
        $transaction->commit();
        Yii::$app->response->setStatusCode(201);
        return $this->prepareDataProvider(Yii::$app->request->get('product_id')); // Возвращаем список изображений для продукта

    } catch (\Exception $e) {
        $transaction->rollBack();
        throw $e;
    }
}

public function actionUpdate($id)
{
    Yii::debug('actionUpdate called for id: ' . $id, 'product-images');

    // Проверяем заголовок X-HTTP-Method-Override
    $methodOverride = Yii::$app->request->headers->get('X-HTTP-Method-Override');
    if ($methodOverride === 'PUT') {
        Yii::debug('X-HTTP-Method-Override: PUT detected', 'product-images');
        // Обрабатываем как PUT запрос
        $model = $this->findModel($id);

        // Получаем данные из $_POST
        $model->attributes = $_POST;
        Yii::debug('Model attributes after load from $_POST: ' . json_encode($model->attributes), 'product-images');

        $transaction = Yii::$app->db->beginTransaction(); // Начинаем транзакцию
        try {

            // Обрабатываем загрузку файла вручную
            if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
                $imageFile = new UploadedFile([
                    'name' => $_FILES['imageFile']['name'],
                    'tempName' => $_FILES['imageFile']['tmp_name'],
                    'type' => $_FILES['imageFile']['type'],
                    'size' => $_FILES['imageFile']['size'],
                    'error' => $_FILES['imageFile']['error'],
                ]);

                if (!$this->validateImageFile($imageFile)) {
                    throw new UnprocessableEntityHttpException('Invalid image file.');
                }

                // Удаляем старый файл
                $this->deleteImageFile($model->url);

                // Сохраняем новый файл
                try {
                    $model->url = $this->saveImageFile($imageFile);
                } catch (\Exception $e) {
                    throw new ServerErrorHttpException('Failed to save image file: ' . $e->getMessage());
                }
            }

            if ($model->save()) {
                $transaction->commit(); // Подтверждаем транзакцию
                Yii::debug('Model saved successfully', 'product-images');
                $model->is_main = (int) $model->is_main;
                $model->sort_order = (int) $model->sort_order;
                return $model;
            } else {
                throw new UnprocessableEntityHttpException(json_encode($model->errors));
            }

        } catch (\Exception $e) {
            $transaction->rollBack(); // Откатываем транзакцию
            Yii::error('Error saving model: ' . $e->getMessage(), 'product-images');
            throw $e;
        }

    } else {
        // Если X-HTTP-Method-Override не указан или не равен PUT, возвращаем ошибку
        throw new BadRequestHttpException('Use POST with X-HTTP-Method-Override: PUT for updates.');
    }
}

public function actionDelete($id)
{
    $model = $this->findModel($id);
    $productId = $model->product_id; // Запоминаем product_id
    $transaction = Yii::$app->db->beginTransaction(); // Начинаем транзакцию

    try {
        $this->deleteImageFile($model->url); // Удаляем файл с сервера
    } catch (\Exception $e){
        Yii::error('Error deleting image: ' . $e->getMessage());
    }

    try {
        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object.');
        }
        Yii::$app->getResponse()->setStatusCode(204);

        // Получаем оставшиеся изображения и пересчитываем sort_order
        $images = ProductImages::find()
            ->where(['product_id' => $productId])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        foreach ($images as $index => $image) {
            $image->sort_order = $index + 1;
            if (!$image->save(false)) { // Отключаем валидацию, чтобы избежать проблем
                throw new ServerErrorHttpException('Failed to reorder images.');
            }
        }

        $transaction->commit(); // Подтверждаем транзакцию
    } catch (\Exception $e) {
        $transaction->rollBack(); // Откатываем транзакцию в случае ошибки
        throw $e; // Перебрасываем исключение дальше
    }
}

    protected function findModel($id)
    {
        if (($model = $this->modelClass::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('ProductImage not found.');
    }

    private function validateImageFile(UploadedFile $file): bool
    {
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB
        Yii::debug('File type: ' . $file->type, 'product-images');
        if (!in_array($file->type, $allowedMimeTypes)) {
            return false;
        }

        if ($file->size > $maxFileSize) {
            return false;
        }

        return true;
    }

    private function saveImageFile(UploadedFile $file): string
    {
        $uploadPath = Yii::getAlias('@webroot/uploads/products/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $fileName = uniqid() . '.' . $file->extension;
        $filePath = $uploadPath . $fileName;

        if (!$file->saveAs($filePath)) {
            throw new ServerErrorHttpException('Failed to save uploaded file.');
        }

        return '/uploads/products/' . $fileName;
    }

    protected function deleteImageFile($url)
{
    $filePath = Yii::getAlias('@webroot') . $url;
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            Yii::info('File deleted successfully: ' . $filePath, 'product-images');
        } else {
            Yii::error('Failed to delete file: ' . $filePath, 'product-images');
        }
    } else {
        Yii::warning('File not found: ' . $filePath, 'product-images');
    }
}

     private function resetMainImage($productId, $currentImageId = null)
    {
        $query = $this->modelClass::find()->where(['product_id' => $productId, 'is_main' => 1]);

        if ($currentImageId !== null) {
            $query->andWhere(['!=', 'id', $currentImageId]);
        }

        foreach ($query->all() as $image) {
            $image->is_main = 0;
            $image->save(false); // Skip validation
        }
    }
}