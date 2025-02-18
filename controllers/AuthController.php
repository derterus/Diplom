<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\LoginForm;
use app\models\User;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class AuthController extends Controller
{
    public $enableCsrfValidation = false; // Disable CSRF for API endpoint

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $contentType = $request->getContentType();
    
        if ($contentType === 'application/json') {
            $data = json_decode($request->getRawBody(), true);
        } else {
            $data = $request->post();
        }
    
        $model = new LoginForm();
        $model->load($data, ''); // Load data
    
        if (!$model->validate()) {
            Yii::debug('LoginForm validation failed: ' . print_r($model->getErrors(), true), 'login'); // Логируем ошибки валидации
            throw new UnauthorizedHttpException('Incorrect username or password.'); // Неправильные учетные данные
        }
    
        if ($user = $model->login()) {
            Yii::debug('Login successful for user: ' . $user->username . ', role: ' . $user->role, 'login'); // Логируем успешный вход
    
            // Устанавливаем роль пользователя в Yii::$app->user->identity
            $identity = Yii::$app->user->getIdentity();
            $identity->role = $user->role; // Предполагается, что в модели User есть свойство 'role'
    
            Yii::debug('User role after login: ' . Yii::$app->user->identity->role, 'auth'); // Логируем роль после установки
    
            return [
                'message' => 'Logged in successfully.',
                'access_token' => $user->access_token, //  Возвращает access_token
                'user' => $user->toArray(),
            ];
        } else {
            Yii::debug('Login failed', 'login'); // Логируем неудачный вход
            throw new UnauthorizedHttpException('Incorrect username or password.'); // Неправильные учетные данные
        }
    }
}