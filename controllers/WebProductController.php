<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\httpclient\Client;
use app\models\Characteristics;

class WebProductController extends Controller
{
    public function actionView($id)
    {
        // URL вашего API
        $apiUrl = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl . '/api/products/view/' . $id;

        // Bearer токен
        $token = Yii::$app->params['apiToken'];

        // Вывод URL и токена для отладки
        Yii::info("API URL: " . $apiUrl);
        Yii::info("API Token: " . $token);

        // Создаем HTTP-клиент
        $client = Yii::$app->httpclient;

        // Отправляем GET-запрос к API с Bearer токеном
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($apiUrl)
            ->addHeaders(['Authorization' => 'Bearer ' . $token])
            ->send();

        // Вывод ответа для отладки
        Yii::info("API Response: " . print_r($response->data, true));

        // Проверяем статус ответа
        if ($response->isOk) {
            $data = $response->data;

            // Получаем имена характеристик из базы данных
            $characteristicIds = array_column($data['characteristics'], 'characteristic_id');
            $characteristicsWithNames = Characteristics::find()
                ->where(['id' => $characteristicIds])
                ->indexBy('id')
                ->all();

            // Добавляем имена характеристик к данным
            foreach ($data['characteristics'] as &$characteristic) {
                $characteristicId = $characteristic['characteristic_id'];
                if (isset($characteristicsWithNames[$characteristicId])) {
                    $characteristic['name'] = $characteristicsWithNames[$characteristicId]->name;
                } else {
                    $characteristic['name'] = 'Unknown';
                }
            }

            return $this->render('view', [
                'model' => $data['product'],
                'mainImage' => $data['mainImage'],
                'characteristics' => $data['characteristics'],
                'reviews' => $data['reviews'],
                'similarProducts' => $data['similarProducts'],
            ]);
        } else {
            throw new NotFoundHttpException('The requested product does not exist.');
        }
    }
}