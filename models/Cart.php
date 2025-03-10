<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Cart extends Model
{
    public $items = [];

    public function init()
    {
        parent::init();
        $this->items = Yii::$app->session->get('cart', []);
    }

    public function addItem($productId, $quantity = 1)
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] += $quantity;
        } else {
            $this->items[$productId] = [
                'product_id' => $productId,
                'quantity' => $quantity,
            ];
        }
        $this->save();
    }

    public function removeItem($productId)
    {
        if (isset($this->items[$productId])) {
            unset($this->items[$productId]);
            $this->save();
        }
    }

    public function updateItem($productId, $quantity)
    {
        if (isset($this->items[$productId])) {
            $this->items[$productId]['quantity'] = $quantity;
            $this->save();
        }
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $product = Products::findOne($item['product_id']);
            if ($product) {
                $total += $product->price * $item['quantity'];
            }
        }
        return $total;
    }

    public function save()
    {
        Yii::$app->session->set('cart', $this->items);
    }

    public function clear()
    {
        $this->items = [];
        $this->save();
    }
}