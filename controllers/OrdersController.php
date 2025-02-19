<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Orders; 
use app\models\OrderItems; 
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;

class OrdersController extends ActiveController
{
    public $modelClass = 'app\models\Orders'; 

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

        // Отключаем actions 'delete', 'create', 'update' (используем свои)
        unset($actions['delete'], $actions['create'], $actions['update']);

        // Настройка prepareDataProvider для actionIndex
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $query = Orders::find(); // Исправлено

        // Фильтрация по user_id (пример)
        $userId = Yii::$app->request->get('user_id');
        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }

        // Фильтрация по статусу (пример)
        $status = Yii::$app->request->get('status');
        if ($status !== null) {
            $query->andWhere(['status' => $status]);
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
        $order = $this->findModel($id);
        return $order->toArray(); // Возвращаем массив
    }

    public function actionCreate()
{
    $request = Yii::$app->request;
    $orderData = $request->getBodyParams();

    // Валидация данных заказа (можно использовать сценарии валидации)
    $order = new Orders();
    $order->load($orderData, '');
    $order->user_id = Yii::$app->user->id;

    // Получаем элементы заказа из запроса
    $orderItemsData = $orderData['orderItems'] ?? []; // Предполагаем, что элементы заказа приходят в массиве 'orderItems'

    // Начинаем транзакцию
    $transaction = Yii::$app->db->beginTransaction();
    try {
        if (!$order->save()) {
            throw new UnprocessableEntityHttpException(json_encode($order->getErrors()));
        }

        // Создаем элементы заказа
        foreach ($orderItemsData as $itemData) {
            $orderItem = new OrderItems();
            $itemData['order_id'] = $order->id; // Привязываем к созданному заказу
            $orderItem->load($itemData, '');

            if (!$orderItem->save()) {
                throw new UnprocessableEntityHttpException(json_encode($orderItem->getErrors()));
            }
        }

        // Подтверждаем транзакцию
        $transaction->commit();

        $response = Yii::$app->getResponse();
        $response->setStatusCode(201);
        $id = implode(',', array_values($order->getPrimaryKey(true)));
        $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute(['view', 'id' => $id], true));
        return $order;

    } catch (\Exception $e) {
        $transaction->rollBack();
        Yii::error('Failed to create order: ' . $e->getMessage()); // Логируем ошибку
        throw $e; // Пробрасываем исключение дальше
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

    public function actionChangeStatus($id)
    {
        $model = $this->findModel($id);
        $newStatus = Yii::$app->request->getBodyParam('status');

        if ($newStatus === null) {
            throw new UnprocessableEntityHttpException('Status parameter is required.');
        }

        $model->status = $newStatus;

        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the order status for unknown reason.');
        } else {
            throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
        }
    }

    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) { // Исправлено
            return $model;
        }

        throw new NotFoundHttpException('Order not found.');
    }
}