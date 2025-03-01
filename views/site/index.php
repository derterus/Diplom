<?php

/** @var yii\web\View $this */
/** @var array $products */
/** @var array $newArrivals */
/** @var array $bestDeals */
/** @var array $manufacturers */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Главная страница - Магазин смарт-часов';
?>

<div class="site-index">

    <!-- Hero Section -->
    <section class="bg-bg-color py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold text-primary-dark mb-8">Технологии будущего на вашем запястье</h1>
            <p class="text-xl text-text-color mb-12">
                Откройте для себя мир смарт-часов. Отслеживайте свою активность, получайте уведомления и оставайтесь всегда на связи.
            </p>
            <a href="<?= Url::to(['product/index']) ?>" class="btn-primary">Смотреть все модели</a>
        </div>
    </section>

<!-- Раздел скидок -->
<div class="promo-discount py-12">
    <h3 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
        <i class="fas fa-percent mr-2"></i> Лучшие скидки
    </h3>
    <div class="relative">
        <!-- Кнопка "назад" -->
        <button id="scroll-left-discount" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-3 rounded-full shadow-md z-10">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- Контейнер для горизонтальной прокрутки -->
        <div id="discount-slider" class="flex overflow-x-auto space-x-6 px-6 no-scrollbar">
            <?php foreach ($bestDeals as $product): ?>
                <div class="product-card flex-shrink-0 w-60">
                    <img class="product-image" src="<?= Html::encode($product['image']) ?>" alt="<?= Html::encode($product['name']) ?>" />
                    <div class="product-info">
                        <h4 class="product-name"><?= Html::encode($product['name']) ?></h4>
                        <div class="price-row flex items-center mt-auto">
                            <?php if ($product['discount_percentage'] > 0): ?>
                                <p class="discount-price"><?= Html::encode($product['price']) ?> ₽</p>
                                <p class="current-price text-accent-color"><?= Html::encode($product['price'] * (1 - $product['discount_percentage'] / 100)) ?> ₽</p>
                            <?php else: ?>
                                <p class="current-price"><?= Html::encode($product['price']) ?> ₽</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="<?= Url::to(['product/view', 'id' => $product['id']]) ?>" class="btn-more">Подробнее</a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Кнопка "вперед" -->
        <button id="scroll-right-discount" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-3 rounded-full shadow-md z-10">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>


<!-- Раздел новинок -->
<div class="promo-new-arrivals py-12 bg-orange-50">
    <h3 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
        <i class="fas fa-watch mr-2"></i> Новинки
    </h3>
    <div class="relative">
        <!-- Кнопка "назад" -->
        <button id="scroll-left-new-arrivals" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-3 rounded-full shadow-md z-10">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- Контейнер для горизонтальной прокрутки -->
        <div id="new-arrivals-slider" class="flex overflow-x-auto space-x-6 px-6 no-scrollbar">
            <?php foreach ($newArrivals as $product): ?>
                <div class="product-card flex-shrink-0 w-60">
                    <img class="product-image" src="<?= Html::encode($product['image']) ?>" alt="<?= Html::encode($product['name']) ?>" />
                    <div class="product-info">
                        <h4 class="product-name"><?= Html::encode($product['name']) ?></h4>
                        <div class="price-row flex items-center mt-auto">
                            <?php if ($product['discount_percentage'] > 0): ?>
                                <p class="discount-price"><?= Html::encode($product['price']) ?> ₽</p>
                                <p class="current-price text-accent-color"><?= Html::encode($product['price'] * (1 - $product['discount_percentage'] / 100)) ?> ₽</p>
                            <?php else: ?>
                                <p class="current-price"><?= Html::encode($product['price']) ?> ₽</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <a href="<?= Url::to(['product/view', 'id' => $product['id']]) ?>" class="btn-more">Подробнее</a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Кнопка "вперед" -->
        <button id="scroll-right-new-arrivals" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-3 rounded-full shadow-md z-10">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>



    <div class="manufacturers-section py-12">
    <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">Наши производители</h2>
    
    <div class="relative flex items-center">
        <!-- Кнопка "назад" -->
        <button id="scroll-left" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-3 rounded-full shadow-md z-10">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- Контейнер для горизонтальной прокрутки -->
        <div id="manufacturers-slider" class="flex overflow-x-auto space-x-6 px-6 scroll-smooth no-scrollbar">
            <?php foreach ($manufacturers as $manufacturer): ?>
                <div class="manufacturer-logo-container flex-shrink-0 w-40">
                    <a href="<?= Url::to(['site/brand', 'id' => $manufacturer['id']]) ?>">
                        <img class="w-full h-auto object-contain" src="<?= Html::encode($manufacturer['logo']) ?>" alt="<?= Html::encode($manufacturer['name']) ?>" />
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Кнопка "вперед" -->
        <button id="scroll-right" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-3 rounded-full shadow-md z-10">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>


    <!-- Раздел с бонусами или рекомендациями -->
    <div class="bonus-section py-12 bg-white">
        <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">Рекомендуем вам</h2>
        <div class="bonus-items grid grid-cols-1 md:grid-cols-3 gap-8 px-6">
            <div class="bonus-item p-8 rounded-3xl shadow-md bg-orange-50">
                <h3 class="text-xl font-semibold text-primary-dark mb-3"><i class="fas fa-shield-alt mr-2"></i> Гарантия качества</h3>
                <p class="text-text-color">Все наши часы проходят строгий контроль качества, чтобы обеспечить вам долгосрочную эксплуатацию и надежность.</p>
            </div>
            <div class="bonus-item p-8 rounded-3xl shadow-md bg-orange-50">
                <h3 class="text-xl font-semibold text-primary-dark mb-3"><i class="fas fa-shipping-fast mr-2"></i> Бесплатная доставка</h3>
                <p class="text-text-color">Мы предлагаем бесплатную доставку для всех заказов на сумму от 5000 ₽.</p>
            </div>
            <div class="bonus-item p-8 rounded-3xl shadow-md bg-orange-50">
                <h3 class="text-xl font-semibold text-primary-dark mb-3"><i class="fas fa-headset mr-2"></i> Поддержка 24/7</h3>
                <p class="text-text-color">Наша служба поддержки готова ответить на ваши вопросы и помочь в любой ситуации.</p>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const slider = document.getElementById("manufacturers-slider");
    const btnLeft = document.getElementById("scroll-left");
    const btnRight = document.getElementById("scroll-right");

    const scrollAmount = 200; // Количество пикселей для прокрутки

    btnLeft.addEventListener("click", function () {
        slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    btnRight.addEventListener("click", function () {
        slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });

    // Предотвращаем прокрутку вверх и вниз
    slider.addEventListener("wheel", function (e) {
        if (e.deltaY !== 0) {
            e.preventDefault();
            slider.scrollBy({
                left: e.deltaY > 0 ? scrollAmount : -scrollAmount,
                behavior: "smooth"
            });
        }
    });
});
document.addEventListener("DOMContentLoaded", function () {
    function setupSlider(sliderId, btnLeftId, btnRightId) {
        const slider = document.getElementById(sliderId);
        const btnLeft = document.getElementById(btnLeftId);
        const btnRight = document.getElementById(btnRightId);
        const scrollAmount = 300; // Увеличил прокрутку для плавности

        function checkButtons() {
            btnLeft.style.opacity = slider.scrollLeft > 0 ? "1" : "0.3";
            btnRight.style.opacity = slider.scrollLeft + slider.clientWidth < slider.scrollWidth ? "1" : "0.3";
        }

        btnLeft.addEventListener("click", function () {
            slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
            setTimeout(checkButtons, 300);
        });

        btnRight.addEventListener("click", function () {
            slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
            setTimeout(checkButtons, 300);
        });

        checkButtons(); // Проверяем начальное состояние кнопок
    }

    setupSlider("discount-slider", "scroll-left-discount", "scroll-right-discount");
    setupSlider("new-arrivals-slider", "scroll-left-new-arrivals", "scroll-right-new-arrivals");
    setupSlider("manufacturers-slider", "scroll-left", "scroll-right");
});

</script>