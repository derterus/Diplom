<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Orders; // Исправлено
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;

class OrdersController extends ActiveController
{
    public $modelClass = 'app\models\Orders'; // Исправлено

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
                    'actions' => ['index', 'view', 'create'], // Allow create for authenticated users
                    'roles' => ['@'], // Авторизованные пользователи
                ],
                [
                    'allow' => true,
                    'actions' => ['update', 'delete', 'change-status'], // Only admin can update and delete
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
        $model = new Orders(); // Исправлено
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = Yii::$app->user->id; // Set user_id for the current user
        $model->created_at = date('Y-m-d H:i:s'); // Set created_at timestamp

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