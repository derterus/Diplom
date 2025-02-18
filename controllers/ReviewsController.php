<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Reviews;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;

class ReviewsController extends ActiveController
{
    public $modelClass = 'app\models\Reviews';

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
                    'actions' => ['update', 'delete', 'approve'], // Only admin can update, delete and approve
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
        $query = Reviews::find(); // Исправлено

        // Фильтрация по product_id (пример)
        $productId = Yii::$app->request->get('product_id');
        if ($productId !== null) {
            $query->andWhere(['product_id' => $productId]);
        }

        // Только одобренные отзывы (пример)
        $approvedOnly = Yii::$app->request->get('approved_only');
        if ($approvedOnly === '1') {
            $query->andWhere(['is_approved' => 1]);
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
        $review = $this->findModel($id);
        return $review->toArray(); // Возвращаем массив
    }

    public function actionCreate()
    {
        $model = new Reviews(); 
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        $model->user_id = Yii::$app->user->id; // Set user_id for the current user

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
        if (!Yii::$app->user->can('updateReview', ['review' => $model])) { // Check permission
            throw new ForbiddenHttpException('You are not allowed to update this review.');
        }
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
        if (!Yii::$app->user->can('deleteReview', ['review' => $model])) { // Check permission
            throw new ForbiddenHttpException('You are not allowed to delete this review.');
        }

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        $model->is_approved = 1; // Approve the review

        if ($model->save()) {
            return $model;
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to approve the review for unknown reason.');
        } else {
            throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
        }
    }

    protected function findModel($id)
    {
        if (($model = Reviews::findOne($id)) !== null) { // Исправлено
            return $model;
        }

        throw new NotFoundHttpException('Review not found.');
    }
}