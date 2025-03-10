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
use app\models\User;
use app\models\RegistrationForm;
use yii\widgets\ActiveForm;
use yii\helpers\Json;

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

    

     public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $client = new Client();
            $apiUrl = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/auth/login';

            try {
                $response = $client->post($apiUrl, [
                    'username' => $model->username,
                    'password' => $model->password,
                ])->send();

                if ($response->isOk) {
                    $data = $response->data;
                    Yii::$app->session->set('access_token', $data['access_token']);
                    Yii::$app->user->login(User::findIdentity($data['user']['id']), 3600 * 24 * 30);
                    
                    return $this->goBack();
                } else {
                    Yii::$app->session->setFlash('error', 'Invalid login credentials.');
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Login failed: ' . $e->getMessage());
            }
        }

        $model->password = '';
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->remove('access_token');

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
    public function actionRegister()
{
    $model = new RegistrationForm();

    if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        // Валидация модели
        if ($model->validate()) {
            return ['success' => true];
        } else {
            // Возвращаем ошибки в формате JSON
            return ActiveForm::validate($model);
        }
    }

    return $this->render('register', ['model' => $model]);
}
public function actionCatalog()
{
    $token = Yii::$app->params['apiToken']; $token = Yii::$app->params['apiToken'];
    $client = new Client();

    try {
        // Собираем параметры фильтров
        $filters = Yii::$app->request->get('filters', []);

        // Запрос к API продуктов с фильтрами
        $productResponse = $client->get(Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/api/products', [
            'filters' => json_encode($filters) // Передаем фильтры
        ], [
            'Authorization' => 'Bearer ' . $token,
        ])->send();

        if (!$productResponse->isOk) {
            throw new HttpException($productResponse->statusCode, 'Ошибка при получении продуктов');
        }

        $products = $productResponse->data['products'];

        // Запрос к API характеристик
        $charResponse = $client->get(Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/api/characteristics', [], [
            'Authorization' => 'Bearer ' . $token,
        ])->send();

        if (!$charResponse->isOk) {
            throw new HttpException($charResponse->statusCode, 'Ошибка при получении характеристик');
        }

        $characteristics = $charResponse->data;

        if (Yii::$app->request->isAjax) {
            return $this->asJson(['products' => $products]); // Возвращаем JSON для AJAX-запроса
        }

    } catch (\Exception $e) {
        throw new HttpException(500, 'Ошибка при запросе к API: ' . $e->getMessage());
    }

    return $this->render('catalog', [
        'products' => $products,
        'characteristics' => $characteristics,
    ]);
}

}
