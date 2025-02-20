<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Orders; 
use app\models\OrderItems; 
use app\models\Products;
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
                    'actions' => ['create', 'update', 'delete','cancel'],
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

        // Получаем ID пользователя из токена
        $user_id = Yii::$app->user->id;

        // Данные заказа
        $order = new Orders();
        $order->user_id = $user_id;
        $order->shipping_address = $request->post('shipping_address');
        $order->payment_method = $request->post('payment_method');
        $order->shipping_cost = $request->post('shipping_cost');
        $order->total_amount = 0; // Рассчитаем позже

        // Устанавливаем статус заказа
        $order->status = 'pending'; // Или любой другой статус по умолчанию

        // Генерируем номер отслеживания
        $order->tracking_number = $this->generateTrackingNumber();

        if ($order->save()) {
            $totalAmount = 0;

            // Получаем orderItems
            $orderItems = $request->post('orderItems', []);

            foreach ($orderItems as $item) {
                $orderItem = new OrderItems();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->discount = isset($item['discount']) ? $item['discount'] : 0;

                // Получаем цену товара
                $product = Products::findOne($orderItem->product_id);
                if ($product) {
                    $orderItem->price = $product->price; // Set price from product table
                    $price = $product->price * $orderItem->quantity - $orderItem->discount;
                    $totalAmount += $price;

                    $orderItem->item_total = $price; // Save total
                    $orderItem->save();
                }
                
            }

            // Обновляем общую сумму заказа
            $order->total_amount = $totalAmount + $order->shipping_cost;
            $order->save();

            return ['success' => true, 'message' => 'Заказ успешно создан', 'order_id' => $order->id];
        }

        return ['success' => false, 'message' => 'Ошибка при создании заказа', 'errors' => $order->errors];
    }

    private function generateTrackingNumber()
    {
        return uniqid('TRACK-'); // или любой другой способ генерации номера
    }
    public function actionCancel($id)
{
    $order = $this->findModel($id);

    // Проверяем, что заказ можно отменить (например, он не находится в статусе "отправлен")
    if ($order->status === 'shipped') {
        throw new UnprocessableEntityHttpException('Cannot cancel a shipped order.');
    }

    $order->status = 'canceled';
    if ($order->save()) {
        return ['success' => true, 'message' => 'Order canceled successfully.'];
    } else {
        throw new ServerErrorHttpException('Failed to cancel order.');
    }
}

public function actionDelete($id)
{
    $order = $this->findModel($id);

    // Начинаем транзакцию
    $transaction = Yii::$app->db->beginTransaction();
    try {
        // Удаляем элементы заказа
        OrderItems::deleteAll(['order_id' => $order->id]);

        // Удаляем заказ
        $order->delete();

        $transaction->commit();

        Yii::$app->getResponse()->setStatusCode(204); // No Content

    } catch (\Exception $e) {
        $transaction->rollBack();
        Yii::error('Failed to delete order: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), 'orders');
        throw new ServerErrorHttpException('Failed to delete order.');
    }
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