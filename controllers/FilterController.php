<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Products;
use app\models\ProductCharacteristics;
use yii\web\HttpException;

class FilterController extends Controller
{
    public function actionFilter()
    {
        // Получение фильтров из запроса
        $filters = Yii::$app->request->get('filters', []);

        // Log the filters
        Yii::info("Filters: " . print_r($filters, true), 'test-product');

        // Проверка, если фильтры в виде JSON строки, то декодируем их
        if (is_string($filters)) {
            $filters = json_decode($filters, true);
        }

        // Log the decoded filters
        Yii::info("Decoded Filters: " . print_r($filters, true), 'test-product');

        // Проверяем, что теперь filters - это массив
        if (!is_array($filters)) {
            return $this->asJson(['error' => 'Некорректные фильтры']);
        }

        // Строим запрос для фильтрации продуктов
        $query = Products::find();

        // SQL-запрос
        $sql = "SELECT p.* FROM products p WHERE 1=1";

        // Массив для хранения подзапросов
        $subQueries = [];

        // Применяем фильтры
        foreach ($filters as $key => $filterValues) {
            // Extract the characteristic ID from the key using regular expression
            if (preg_match('/filters\[(\d+)\]\[\]/', $key, $matches)) {
                $characteristic_id = $matches[1]; // Extracted characteristic ID

                // Log the extracted characteristic ID and filter values
                Yii::info("Characteristic ID: " . $characteristic_id . ", Filter Values: " . print_r($filterValues, true), 'test-product');

                // Условие OR для значений одной характеристики
                $orConditions = [];
                foreach ($filterValues as $index => $filterValue) {
                    $orConditions[] = " (pc.characteristic_id = $characteristic_id AND pc.value = '" . addslashes($filterValue) . "') ";
                }

                // Добавляем условие OR к подзапросу
                if (!empty($orConditions)) {
                    $subQueries[] = "SELECT product_id FROM product_characteristics pc WHERE " . implode(" OR ", $orConditions);
                }
            }
        }

        // Добавляем подзапросы к основному запросу
        if (!empty($subQueries)) {
            $sql .= " AND p.id IN (" . implode(" INTERSECT ", $subQueries) . ")";
        }

        // Выполняем запрос
        $filteredProducts = Yii::$app->db->createCommand($sql)->queryAll();

        // Формируем данные для ответа
        $productsData = [];
        $baseUrl = Yii::$app->request->baseUrl; // Получаем baseUrl

        foreach ($filteredProducts as $product) {
            // Retrieve the product model based on the 'id' from the query result
            $productModel = Products::findOne($product['id']);

            $images = [];
            if ($productModel) {
                foreach ($productModel->productImages as $image) {
                    // Убираем лишний слеш, если он есть
                    $imageUrl = $baseUrl .  $image->url;
                    $images[] = $imageUrl;
                }
            }

            $productsData[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'description' => $product['description'],
                'image' => $images[0] ?? null,
                'discount_percentage' => $productModel ? $productModel->discount_percentage : 0, // Added discount_percentage
            ];
        }

        return $this->asJson([
            'products' => $productsData,
        ]);
    }
}