<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'WZTZiG-MCBxphBcHRILFldyLnogGSLqD',
            
        ],
        
        'httpclient' => [
            'class' => 'yii\httpclient\Client',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
       'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0, // Уровень трассировки для отладки
    'targets' => [
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['test-category', 'file-upload'], // Указываем нужные категории для логов
            'logFile' => '@runtime/logs/app.log',
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info'],
            'categories' => ['yii\db\*'], // Логируем SQL-запросы
            'logFile' => '@runtime/logs/sql.log', // Указываем файл для логов
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['login'], // Логируем все действия входа
            'logFile' => '@runtime/logs/login.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['auth'], // Логируем аутентификацию
            'logFile' => '@runtime/logs/auth.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['categories'], // Логируем все действия с категориями
            'logFile' => '@runtime/logs/categ.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['product-images'], // Логируем все действия с загрузкой файлов
            'logFile' => '@runtime/logs/file-upload.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['model-save'], // Логируем сохранение модели
            'logFile' => '@runtime/logs/model-save.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['validation'], // Логируем ошибки валидации
            'logFile' => '@runtime/logs/validation.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['request'], // Логируем данные запросов
            'logFile' => '@runtime/logs/request.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['test-product'], // Логируем данные запросов
            'logFile' => '@runtime/logs/test-product.log', // Куда сохранять логи
        ],
        [
            'class' => 'yii\log\FileTarget',
            'levels' => ['error', 'warning', 'info', 'trace'],
            'categories' => ['orders'], // Логируем данные запросов
            'logFile' => '@runtime/logs/orders.log', // Куда сохранять логи
        ],
        ],
    ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
        'rules' => [
            
                'POST api/categories/<id:\d+>' => 'categories/update',
                'POST api/characteristics/<id:\d+>'  => 'characteristics/update',
                'POST api/manufacturers/<id:\d+>'  => 'manufacturers/update',
                'POST api/products/<id:\d+>'  => 'products/update',
                'POST api/orders/<id:\d+>/cancel' => 'orders/cancel',
                ['class' => 'yii\rest\UrlRule', 'controller' => 'characteristics', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'categories', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'manufacturers', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'products', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'product-images', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'product-characteristics', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'users', 'prefix' => 'api'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'orders', 'prefix' => 'api'],
        

        'POST registration/register' => 'registration/register',
        'POST auth/login' => 'auth/login',

        // Site routes
        '/' => 'site/index',
        '/about' => 'site/about',
        '/contact' => 'site/contact',
        '/registration' => 'site/registration',
        '/category/<id:\d+>' => 'site/category',
        '/product/<id:\d+>' => 'site/product',
    ],
],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
