<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\factories\CharacteristicsFactory;
use app\factories\ManufacturerFactory;
use app\factories\ProductsFactory;

class GenerateDataController extends Controller
{
    public function actionGenerateCharacteristics()
    {
        $factory = new CharacteristicsFactory(Yii::$app->db);
        $factory->create(10); // Генерируем 10 фейковых характеристик
        echo "Fake characteristics generated successfully.\n";
    }
    public function actionGenerateManufacturers()
    {
        $factory = new ManufacturerFactory(Yii::$app->db);
        $factory->create(10); // Генерируем 10 производителей
        echo "Fake manufacturers generated successfully.\n";
    }
    public function actionGenerateProducts($count = 10)
    {
        $factory = new ProductsFactory(Yii::$app->db);
        $factory->create($count); // Генерируем указанное количество товаров (по умолчанию 10)
        echo "Fake products generated successfully.\n";
    }

}
