<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller; // Заменили ActiveController на Controller
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Categories;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;

class CategoriesController extends Controller
{
    public $modelClass = 'app\models\Categories';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Убираем стандартное поведение для всех методов контроллера
        unset($behaviors['verbs']);  // Убираем автоматическое поведение для всех методов, включая create, update и т.д.

        // Настройка аутентификации через Bearer Token
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        // Контроль доступа
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['@'], // Авторизованные пользователи
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->getRole() === 'admin'; // Проверка роли пользователя
                    },
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException('У вас недостаточно прав для выполнения этого действия.');
            },
        ];

        return $behaviors;
    }

    // Переопределяем actionCreate
    public function actionCreate()
    {
        $model = new Categories();
        
        if (Yii::$app->request->isPost) {
            // Загружаем данные из запроса
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');
            
            // Логируем данные перед сохранением
            Yii::debug('Category data before save: ' . json_encode($model->attributes), 'test-category');

            // Обработка изображения
            $file = UploadedFile::getInstanceByName('image');
            if ($file) {
                $filePath = $this->uploadFile($file);
                if ($filePath) {
                    $model->image = $filePath;
                } else {
                    Yii::info('Failed to upload file during create', 'test-category');
                    throw new UnprocessableEntityHttpException('Failed to upload file.');
                }
            }
            
            // Сохраняем модель
            if ($model->save()) {
                Yii::info('Category created successfully.', 'test-category');
                return $model;
            } else {
                Yii::info('Failed to save category: ' . json_encode($model->errors), 'test-category');
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
        }
    }

    // Переопределяем actionUpdate
    public function actionUpdate($id)
{
    // Получаем модель категории по ID
    $model = $this->findModel($id);

    // Получаем все данные из запроса
    $data = Yii::$app->request->post();
    // Получаем файл изображения
    $file = UploadedFile::getInstanceByName('image'); // Имя поля в form-data (важно)

    // Загружаем данные в модель
    if (!$model->load($data, '')) {
        Yii::info('Failed to load data into model.', 'test-category');
        throw new UnprocessableEntityHttpException('Failed to load data.');
    }

    // Логируем данные перед обновлением
    Yii::debug('Category data before update: ' . json_encode($model->attributes), 'test-category');

    // Обрабатываем изображение (если есть)
    if ($file) {
        // Если существует старое изображение, удаляем его
        if ($model->image) {
            $oldImagePath = Yii::getAlias('@webroot') . $model->image;
            if (file_exists($oldImagePath)) {
                // Логируем перед удалением
                Yii::info('Deleting old image file: ' . $oldImagePath, 'test-category');
                unlink($oldImagePath);
            }
        }

        // Загружаем новый файл
        $filePath = $this->uploadFile($file);
        if ($filePath) {
            // Обновляем путь к изображению в модели
            $model->image = $filePath;
        } else {
            Yii::info('Failed to upload file during update', 'test-category');
            throw new UnprocessableEntityHttpException('Failed to upload file.');
        }
    }

    // Логируем данные после обновления
    Yii::debug('Category data after update: ' . json_encode($model->attributes), 'test-category');

    // Проверяем, были ли изменения
    if (!empty($model->getDirtyAttributes())) {
        if ($model->save()) {
            // После сохранения снова загружаем модель из базы данных, чтобы получить актуальные данные
            $model = Categories::findOne($model->id);

            Yii::info('Category updated successfully.', 'test-category');
            return $model;
        } else {
            Yii::info('Failed to update category: ' . json_encode($model->errors), 'test-category');
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
    } else {
        Yii::info('No changes detected for category update.', 'test-category');
        return $model;
    }
}
    // Переопределяем actionDelete
    public function actionDelete($id)
{
    $model = $this->findModel($id);

    // Проверяем, существует ли изображение и удаляем его
    if ($model->image) {
        $imagePath = Yii::getAlias('@webroot') . $model->image;
        if (file_exists($imagePath)) {
            // Логируем перед удалением
            Yii::info('Deleting image file: ' . $imagePath, 'test-category');

            // Удаляем файл
            unlink($imagePath);
        } else {
            Yii::info('Image file not found: ' . $imagePath, 'test-category');
        }
    }

    // Удаление категории
    if ($model->delete() === false) {
        Yii::info('Failed to delete category: ' . $id, 'test-category');
        throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
    }

    Yii::$app->getResponse()->setStatusCode(204);
}

    // Новый метод для просмотра всех категорий
    public function actionIndex()
    {
        $categories = Categories::find()->all(); // Получаем все категории

        // Если категории найдены, возвращаем их
        if ($categories) {
            return $categories;
        }

        Yii::info('No categories found.', 'test-category');
        throw new NotFoundHttpException('Categories not found.');
    }

    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Category not found.');
    }

    private function uploadFile($file)
    {
        $directory = Yii::getAlias('@webroot/uploads/');
        if (!file_exists($directory)) {
            Yii::debug('Directory does not exist. Creating: ' . $directory);
            mkdir($directory, 0777, true);  // Создаем папку, если ее нет
        }
    
        $fileName = uniqid() . '.' . $file->extension;
        $filePath = $directory . $fileName;
    
        // Логирование имени файла и пути
        Yii::debug('File path: ' . $filePath);
    
        if ($file->saveAs($filePath)) {
            return '/uploads/' . $fileName;
        }
    
        Yii::info('Failed to save file: ' . $filePath);
        return false;
    }
}
