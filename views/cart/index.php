<?php
/* @var $this yii\web\View */
/* @var $products array */
/* @var $total float */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4"><?= Html::encode($this->title) ?></h1>
        <?php if (empty($products)): ?>
            <p class="text-center text-gray-500">Ваша корзина пуста.</p>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-100 text-left">Товар</th>
                            <th class="py-2 px-4 bg-gray-100 text-left">Цена</th>
                            <th class="py-2 px-4 bg-gray-100 text-left">Количество</th>
                            <th class="py-2 px-4 bg-gray-100 text-left">Итого</th>
                            <th class="py-2 px-4 bg-gray-100 text-left">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $item): ?>
                            <?php
                            $product = $item['product'];
                            $quantity = $item['quantity'];
                            ?>
                            <tr>
                                <td class="py-2 px-4"><?= Html::encode($product->name) ?></td>
                                <td class="py-2 px-4"><?= Html::encode($product->price) ?> ₽</td>
                                <td class="py-2 px-4">
                                    <form action="<?= Url::to(['cart/update', 'id' => $product->id]) ?>" method="post">
                                        <input type="hidden" name="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                                        <input type="number" name="quantity" value="<?= $quantity ?>" min="1" class="w-16">
                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-1 px-2 rounded-lg transition-colors duration-200">Обновить</button>
                                    </form>
                                </td>
                                <td class="py-2 px-4"><?= Html::encode($product->price * $quantity) ?> ₽</td>
                                <td class="py-2 px-4">
                                    <a href="<?= Url::to(['cart/remove', 'id' => $product->id]) ?>" class="text-red-500 hover:text-red-600">Удалить</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <p class="text-xl font-bold text-gray-800">Итого: <?= Html::encode($total) ?> ₽</p>
                <a href="<?= Url::to(['cart/clear']) ?>" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">Очистить корзину</a>
            </div>
        <?php endif; ?>
    </div>
</div>  