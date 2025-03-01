<?php
/* @var $this yii\web\View */
/* @var $products array */
/* @var $newArrivals array */
/* @var $bestDeals array */
?>

<h1>Каталог продуктов</h1>

<h2>Все продукты</h2>
<div class="product-list">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" />
            <h3><?= $product['name'] ?></h3>
            <p><?= $product['description'] ?></p>
            <p>Цена: $<?= $product['price'] ?> <span class="discounted-price"><?= $product['discounted_price'] ?> </span></p>
            <p>Скидка: <?= $product['discount_percentage'] ?>%</p>
        </div>
    <?php endforeach; ?>
</div>

<h2>Новые поступления</h2>
<div class="product-list">
    <?php foreach ($newArrivals as $product): ?>
        <div class="product">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" />
            <h3><?= $product['name'] ?></h3>
            <p><?= $product['description'] ?></p>
            <p>Цена: $<?= $product['price'] ?> <span class="discounted-price"><?= $product['discounted_price'] ?> </span></p>
            <p>Скидка: <?= $product['discount_percentage'] ?>%</p>
        </div>
    <?php endforeach; ?>
</div>

<h2>Лучшие предложения</h2>
<div class="product-list">
    <?php foreach ($bestDeals as $product): ?>
        <div class="product">
            <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" />
            <h3><?= $product['name'] ?></h3>
            <p><?= $product['description'] ?></p>
            <p>Цена: $<?= $product['price'] ?> <span class="discounted-price"><?= $product['discounted_price'] ?> </span></p>
            <p>Скидка: <?= $product['discount_percentage'] ?>%</p>
        </div>
    <?php endforeach; ?>
</div>
