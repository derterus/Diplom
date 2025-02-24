<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\httpclient\Client;
use yii\web\HttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */


     public function actionIndex()
     {
         // Получаем токен из параметров конфигурации
         $token = Yii::$app->params['apiToken'];
     
         // Путь к API для продуктов
         $productsApiUrl = Yii::$app->request->baseUrl . '/api/products';
         // Путь к API для производителей
         $manufacturersApiUrl = Yii::$app->request->baseUrl . '/api/manufacturers';
     
         // Создаем HTTP клиент
         $client = new Client();
     
         try {
             // Запрос для получения продуктов
             $productResponse = $client->get(Yii::$app->request->hostInfo . $productsApiUrl, [], [
                 'Authorization' => 'Bearer ' . $token,
             ])->send();
     
             // Запрос для получения производителей
             $manufacturerResponse = $client->get(Yii::$app->request->hostInfo . $manufacturersApiUrl, [], [
                 'Authorization' => 'Bearer ' . $token,
             ])->send();
     
             // Если запросы прошли успешно
             if ($productResponse->isOk && $manufacturerResponse->isOk) {
                 $products = $productResponse->data['products']; // Получаем из products
                 $newArrivals = $productResponse->data['newArrivals']; // Получаем из newArrivals
                 $bestDeals = $productResponse->data['bestDeals']; // Получаем из bestDeals
                 $manufacturers = $manufacturerResponse->data;
             } else {
                 // Если произошла ошибка при запросе
                 throw new HttpException($productResponse->statusCode, 'Error while making API request');
             }
         } catch (\Exception $e) {
             // Обработка ошибок, если что-то пошло не так
             throw new HttpException(500, 'API request failed: ' . $e->getMessage());
         }
     
         // Возвращаем данные в представление
         return $this->render('index', [
             'products' => $products,
             'newArrivals' => $newArrivals,
             'bestDeals' => $bestDeals,
             'manufacturers' => $manufacturers,
         ]);
     }

    

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
