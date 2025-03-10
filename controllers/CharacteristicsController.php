<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\models\Characteristics;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\web\ForbiddenHttpException;

class CharacteristicsController extends Controller
{
    public $modelClass = 'app\models\Characteristics';

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

    public function actionIndex()
{
    $characteristics = Characteristics::find()
        ->with('values') // Загружаем возможные значения характеристик
        ->asArray()
        ->all();

    if ($characteristics) {
        return $characteristics;
    }

    Yii::info('No characteristics found.', 'test-characteristics');
    throw new NotFoundHttpException('Characteristics not found.');
}



    public function actionView($id)
    {
        return $this->findModel($id);
    }

    public function actionCreate()
    {
        $model = new Characteristics();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->getRequest()->getBodyParams(), '');

            if ($model->save()) {
                Yii::info('Characteristic created successfully.', 'test-characteristics');
                return $model;
            } else {
                Yii::info('Failed to save characteristic: ' . json_encode($model->errors), 'test-characteristics');
                throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
            }
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();

        if (!$model->load($data, '')) {
            throw new UnprocessableEntityHttpException('Failed to load data.');
        }

        Yii::debug('Characteristic data before update: ' . json_encode($model->attributes), 'test-characteristics');

        if (!empty($model->getDirtyAttributes())) {
            if ($model->save()) {
                Yii::info('Characteristic updated successfully.', 'test-characteristics');
                return $model;
            } else {
                Yii::info('Failed to update characteristic: ' . json_encode($model->errors), 'test-characteristics');
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        } else {
            Yii::info('No changes detected for characteristic update.', 'test-characteristics');
            return $model;
        }
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete() === false) {
            Yii::info('Failed to delete characteristic: ' . $id, 'test-characteristics');
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    protected function findModel($id)
    {
        if (($model = Characteristics::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Characteristic not found.');
    }
}
