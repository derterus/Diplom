<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller; // Заменили ActiveController на Controller
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Manufacturers;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;

class ManufacturersController extends Controller
{
    public $modelClass = 'app\models\Manufacturers';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['verbs']);

        // Аутентификация через Bearer Token
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
                        return Yii::$app->user->identity->getRole() === 'admin'; // Проверка роли
                    },
                ],
            ],
            'denyCallback' => function () {
                throw new ForbiddenHttpException('У вас недостаточно прав для выполнения этого действия.');
            },
        ];

        return $behaviors;
    }

    public function actionIndex()
{
    // Получаем всех производителей с нужными полями
    $manufacturers = Manufacturers::find()->all();

    // Преобразуем данные в формат массива
    $result = array_map(function ($manufacturer) {
        return [
            'id' => $manufacturer->id,
            'name' => $manufacturer->name,
            'country' => $manufacturer->country,
            'logo' => $manufacturer->logo
                ? Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . $manufacturer->logo
                : null, // Преобразуем путь к логотипу в полный URL
            'website' => $manufacturer->website,
            'contact_email' => $manufacturer->contact_email,
            'phone' => $manufacturer->phone,
            'description' => $manufacturer->description,
        ];
    }, $manufacturers);

    // Возвращаем преобразованные данные
    return $result;
}


    public function actionView($id)
    {
        return $this->findModel($id);
    }

    public function actionCreate()
    {
        $model = new Manufacturers();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');

            $file = UploadedFile::getInstanceByName('logo');
            if ($file) {
                $filePath = $this->uploadFile($file);
                if ($filePath) {
                    $model->logo = $filePath;
                } else {
                    throw new UnprocessableEntityHttpException('Ошибка загрузки файла.');
                }
            }

            if ($model->save()) {
                return $model;
            } else {
                throw new ServerErrorHttpException('Не удалось создать производителя.');
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $file = UploadedFile::getInstanceByName('logo');

        if (!$model->load($data, '')) {
            throw new UnprocessableEntityHttpException('Ошибка загрузки данных.');
        }

        if ($file) {
            if ($model->logo) {
                $oldImagePath = Yii::getAlias('@webroot') . $model->logo;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $filePath = $this->uploadFile($file);
            if ($filePath) {
                $model->logo = $filePath;
            } else {
                throw new UnprocessableEntityHttpException('Ошибка загрузки файла.');
            }
        }

        if ($model->save()) {
            return $model;
        } else {
            throw new ServerErrorHttpException('Не удалось обновить производителя.');
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->logo) {
            $imagePath = Yii::getAlias('@webroot') . $model->logo;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Ошибка удаления производителя.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    protected function findModel($id)
    {
        if (($model = Manufacturers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Производитель не найден.');
    }

    private function uploadFile($file)
    {
        $directory = Yii::getAlias('@webroot/uploads/manufacturers/');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $fileName = uniqid() . '.' . $file->extension;
        $filePath = $directory . $fileName;

        if ($file->saveAs($filePath)) {
            return '/uploads/manufacturers/' . $fileName;
        }

        return false;
    }
}
