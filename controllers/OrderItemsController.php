<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\OrderItems; // Исправлено
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class OrderItemsController extends ActiveController
{
    public $modelClass = 'app\models\OrderItems'; // Исправлено

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
        $query = OrderItems::find(); // Исправлено

        // Фильтрация по order_id (пример)
        $orderId = Yii::$app->request->get('order_id');
        if ($orderId !== null) {
            $query->andWhere(['order_id' => $orderId]);
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
        $orderItem = $this->findModel($id);
        return $orderItem->toArray(); // Возвращаем массив
    }

    public function actionCreate()
    {
        $model = new OrderItems(); // Исправлено
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');

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
        if (($model = OrderItems::findOne($id)) !== null) { // Исправлено
            return $model;
        }

        throw new NotFoundHttpException('OrderItem not found.');
    }
}