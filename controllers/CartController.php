<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Cart;
use app\models\Products;

class CartController extends Controller
{
    public function actionIndex()
    {
        $cart = new Cart();
        $items = $cart->getItems();
        $products = [];

        foreach ($items as $item) {
            $product = Products::findOne($item['product_id']);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                ];
            }
        }

        return $this->render('index', [
            'products' => $products,
            'total' => $cart->getTotal(),
        ]);
    }

    public function actionAdd($id)
    {
        $cart = new Cart();
        $cart->addItem($id);
        return $this->redirect(['index']);
    }

    public function actionRemove($id)
    {
        $cart = new Cart();
        $cart->removeItem($id);
        return $this->redirect(['index']);
    }

    public function actionUpdate($id, $quantity)
    {
        $cart = new Cart();
        $cart->updateItem($id, $quantity);
        return $this->redirect(['index']);
    }

    public function actionClear()
    {
        $cart = new Cart();
        $cart->clear();
        return $this->redirect(['index']);
    }
}