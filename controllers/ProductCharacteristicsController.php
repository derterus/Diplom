<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\ProductCharacteristics; // Adjust namespace
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;

class ProductCharacteristicsController extends ActiveController
{
    public $modelClass = 'app\models\ProductCharacteristics';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['verbs']);

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->getRole() === 'admin';
                    },
                ],
            ],
            'denyCallback' => function ($rule, $action) {
                throw new ForbiddenHttpException('У вас недостаточно прав для выполнения этого действия.');
            },
        ];

        return $behaviors;
    }

    public function actionIndex($product_id) // Get by Product ID
    {
        $productCharacteristics = ProductCharacteristics::find()
            ->where(['product_id' => $product_id])
            ->all();

        if ($productCharacteristics) {
            return $productCharacteristics;
        }

        throw new NotFoundHttpException('No Product Characteristics found for this product.');
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    public function actionCreate()
    {
        $model = new ProductCharacteristics();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');

            if ($model->save()) {
                Yii::info('Product Characteristic created successfully.', 'product-characteristics');
                return $model;
            } else {
                Yii::error('Failed to save product characteristic: ' . json_encode($model->errors), 'product-characteristics');
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
        } else {
            throw new BadRequestHttpException('Method not allowed. Use POST.');
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();

        if (!$model->load($data, '')) {
            throw new UnprocessableEntityHttpException('Failed to load data.');
        }

        if (!empty($model->getDirtyAttributes())) {
            if ($model->save()) {
                Yii::info('Product Characteristic updated successfully.', 'product-characteristics');
                return $model;
            } else {
                Yii::error('Failed to update product characteristic: ' . json_encode($model->errors), 'product-characteristics');
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        } else {
            Yii::info('No changes detected for product characteristic update.', 'product-characteristics');
            return $model;
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete() === false) {
            Yii::error('Failed to delete product characteristic: ' . $id, 'product-characteristics');
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    protected function findModel($id)
    {
        if (($model = ProductCharacteristics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Product Characteristic not found.');
    }
}