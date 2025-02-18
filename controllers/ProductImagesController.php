<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\ProductImages; // Исправлено
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\UploadedFile; // Add this line to use UploadedFile class

class ProductImagesController extends ActiveController
{
    public $modelClass = 'app\models\ProductImages'; // Исправлено

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Аутентификация по Bearer token
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
                    'roles' => ['admin'], // Только админы
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new \yii\web\ForbiddenHttpException('У вас недостаточно прав для выполнения этого действия.');
            },
        ];

        return $behaviors;
    }

     public function actions()
    {
        $actions = parent::actions();

        // Настройка prepareDataProvider для actionIndex
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = ProductImages::find(); // Исправлено

        // Фильтрация по product_id (пример)
        $productId = Yii::$app->request->get('product_id');
        if ($productId !== null) {
            $query->andWhere(['product_id' => $productId]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }

    public function actionView($id)
    {
        $productImage = $this->findModel($id);
        return $productImage->toArray(); // Возвращаем массив
    }

    public function actionCreate()
{
    $model = new ProductImages(); // Исправлено

    // Check if the request is a POST request and if it contains uploaded files
    if (Yii::$app->request->isPost) {
        $model->load(Yii::$app->request->post(), '');

        // Handle file upload if needed
        $imageFile = UploadedFile::getInstance($model, 'url'); // Use UploadedFile class

        if ($imageFile) {
            $uploadPath = 'uploads/product-images/'; // Set your upload path
            $fileName = uniqid() . '.' . $imageFile->extension;
            $filePath = $uploadPath . $fileName;

            if ($imageFile->saveAs($filePath)) {
                $model->url = $filePath; // Save the file path to the model

            } else {
                throw new ServerErrorHttpException('Failed to save uploaded file.');
            }
        }

        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute(['view', 'id' => $id], true));
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        } else {
            throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
        }
    }
}


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        } else {
            throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    protected function findModel($id)
    {
        if (($model = ProductImages::findOne($id)) !== null) { // Исправлено
            return $model;
        }

        throw new NotFoundHttpException('ProductImage not found.');
    }
}