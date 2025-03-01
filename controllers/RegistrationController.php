<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

class RegistrationController extends Controller
{
    public $enableCsrfValidation = false; // Disable CSRF для API endpoint

    public function actionRegister()
    {
        $request = Yii::$app->request;
        $data = json_decode(file_get_contents('php://input'), true); // Parse JSON

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new UnprocessableEntityHttpException('Invalid JSON: ' . json_last_error_msg());
        }

        $model = new User();
        $model->load($data, ''); // Load data from parsed JSON

        // Устанавливаем значение role перед сохранением
        $model->role = 'user';

        // Пытаемся валидировать модель
        if ($model->validate()) {
            // Хэшируем пароль перед сохранением
            $model->setPassword($model->password); 
            $model->generateAuthKey();
            $model->generateAccessToken();

            // Сохраняем модель в БД
            if ($model->save()) {
                // Перезагружаем модель для получения актуальных данных
                $user = User::findOne($model->id);
                $user->refresh(); // Принудительная загрузка данных из БД

                Yii::debug('User role after registration: ' . $user->role, 'auth');

                // Возвращаем успешный ответ
                return [
                    'message' => 'User registered successfully.',
                    'access_token' => $user->access_token,
                    'user' => $this->utf8Encode($user->toArray()), // Преобразуем данные в UTF-8
                ];
            } else {
                // Если сохранение не удалось, возвращаем ошибку
                var_dump($model->getErrors()); // Выводим ошибки
                throw new ServerErrorHttpException('Failed to create the user for an unknown reason.');
            }
        } else {
            // Если модель не прошла валидацию, возвращаем ошибку с детализированными проблемами
            $errors = $this->utf8Encode($model->getErrors());
            throw new UnprocessableEntityHttpException(json_encode($errors));
        }
    }

    // Рекурсивная функция для преобразования данных в UTF-8
    private function utf8Encode($data) {
        if (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'auto');
        }
        if (is_array($data)) {
            return array_map([$this, 'utf8Encode'], $data);
        }
        return $data;
    }
}


