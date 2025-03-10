<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model array */
/* @var $mainImage array */
/* @var $characteristics array */
/* @var $reviews array */
/* @var $similarProducts array */

?>
<div class="container mx-auto py-12">

    <!-- Product Details -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">

            <!-- Product Image -->
            <div class="md:w-1/2">
                <?php if ($mainImage): ?>
                    <img src="<?= Yii::$app->request->baseUrl . Html::encode($mainImage['url']) ?>" alt="<?= Html::encode($model['name']) ?>" class="w-full h-64 object-contain md:h-80 max-w-full">
                <?php else: ?>
                    <p>No main image available</p>
                <?php endif; ?>
            </div>

            <!-- Product Information -->
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-semibold text-gray-800 mb-4"><?= Html::encode($model['name']) ?></h1>

                <div class="flex items-center mb-4">
                    <?= str_repeat('<svg class="w-4 h-4 fill-current text-yellow-500"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 5.19 8.63 0 9.24l5.46 4.73L5.82 21z"/></svg>', 5) ?>
                    <span class="text-gray-500 text-sm ml-1">(5.0)</span>
                </div>

                <p class="text-gray-700 mb-4"><?= Html::encode($model['description']) ?></p>

                <!-- Price & Discount -->
                <div class="flex items-center mb-4">
                    <?php
                    $originalPrice = Html::encode($model['price']);
                    $discountPercentage = isset($model['discount_percentage']) ? Html::encode($model['discount_percentage']) : 0;
                    $discountAmount = $originalPrice * ($discountPercentage / 100);
                    $newPrice = $originalPrice - $discountAmount;
                    ?>

                    <?php if ($discountPercentage > 0): ?>
                        <span class="text-gray-500 line-through text-sm"><?= $originalPrice ?> ₽</span>
                        <span class="text-xl font-bold text-orange-600 ml-2"><?= number_format($newPrice, 0, '.', '') ?> ₽</span>
                        <div class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full ml-2">
                            <?= Html::encode('-' . $discountPercentage . '%') ?>
                        </div>
                    <?php else: ?>
                        <span class="text-xl font-bold text-orange-600"><?= $originalPrice ?> ₽</span>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center">
                    <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 mr-4"><a href="<?= Url::to(['cart/add', 'id' => $model['id']]) ?>" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 mr-4">Купить</a></button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition-colors duration-200">В избранное</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Характеристики</h2>
        <table class="table table-bordered table-striped">
            <tbody>
                <?php foreach ($characteristics as $characteristic): ?>
                    <tr>
                        <th><?= Html::encode($characteristic['name'] ?? 'Unknown') ?></th>
                        <td><?= Html::encode($characteristic['value']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Similar Products Section -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Похожие товары</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($similarProducts as $similarProductData): ?>
                <?php
                $similarProduct = $similarProductData['product'];
                $similarProductMainImage = $similarProductData['mainImage'];
                ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200 relative">
                    <a href="<?= Html::encode(Yii::$app->request->baseUrl . '/web-product/view?id=' . $similarProduct['id']) ?>" class="block">
                        <?php if ($similarProductMainImage && isset($similarProductMainImage['url'])): ?>
                            <img src="<?= Yii::$app->request->baseUrl . Html::encode($similarProductMainImage['url']) ?>" alt="<?= Html::encode($similarProduct['name']) ?>" class="w-full h-64 object-contain max-w-full">
                        <?php else: ?>
                            <p>No main image available</p>
                        <?php endif; ?>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= Html::encode($similarProduct['name']) ?></h3>
                            <p class="text-gray-700 mb-4"><?= Html::encode($similarProduct['description']) ?></p>
                            <div class="flex items-center justify-between mb-4">
                                <?php
                                $similarOriginalPrice = Html::encode($similarProduct['price']);
                                $similarDiscountPercentage = isset($similarProduct['discount_percentage']) ? Html::encode($similarProduct['discount_percentage']) : 0;
                                $similarDiscountAmount = $similarOriginalPrice * ($similarDiscountPercentage / 100);
                                $similarNewPrice = $similarOriginalPrice - $similarDiscountAmount;
                                ?>

                                <?php if ($similarDiscountPercentage > 0): ?>
                                    <span class="text-gray-500 line-through text-sm"><?= $similarOriginalPrice ?> ₽</span>
                                    <span class="text-xl font-bold text-orange-600 ml-2"><?= number_format($similarNewPrice, 0, '.', '') ?> ₽</span>
                                    <div class="bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full ml-2">
                                        <?= Html::encode('-' . $similarDiscountPercentage . '%') ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xl font-bold text-orange-600"><?= $similarOriginalPrice ?> ₽</span>
                                <?php endif; ?>
                            </div>
                            <button class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"><a href="<?= Url::to(['cart/add', 'id' => $similarProduct['id']]) ?>" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">Купить</a></button>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Отзывы</h2>
        <div class="space-y-4">
            <?php foreach ($reviews['items'] as $review): ?>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= Html::encode($review['title']) ?></h3>
                    <p class="text-gray-700 mb-2"><?= Html::encode($review['comment']) ?></p>
                    <div class="flex items-center">
                        <?= str_repeat('<svg class="w-4 h-4 fill-current text-yellow-500"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 5.19 8.63 0 9.24l5.46 4.73L5.82 21z"/></svg>', $review['rating']) ?>
                        <span class="text-gray-500 text-sm ml-1">(<?= Html::encode($review['rating']) ?>)</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>