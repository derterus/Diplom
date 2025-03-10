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

<div class="site-index font-montserrat bg-[var(--bg-color)] text-[var(--text-color)]">
    <!-- Hero Section -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-8 text-center shadow-md">
                <h1 class="text-4xl md:text-5xl font-bold text-black mb-6 drop-shadow-lg tracking-tight">Технологии будущего на вашем запястье</h1>
                <p class="text-lg md:text-xl text-[var(--text-color)] mb-8 max-w-2xl mx-auto leading-relaxed">Откройте для себя мир смарт-часов. Отслеживайте активность, получайте уведомления и оставайтесь на связи.</p>
                <a href="<?= Url::to(['product/index']) ?>" class="inline-block bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-semibold px-6 py-3 rounded-xl transition duration-300 text-md shadow-md">Смотреть все модели</a>
            </div>
        </div>
    </section>

    <!-- Раздел скидок -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-semibold text-black mb-6 text-center drop-shadow-md">
                <i class="fas fa-percent mr-2 text-[var(--primary-color)]"></i> Лучшие скидки
            </h3>
            <div class="relative bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                <button id="scroll-left-discount" class="absolute -left-3 top-1/2 -translate-y-1/2 bg-[var(--block-bg)] p-3 rounded-full shadow-md z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-left text-[var(--primary-color)]"></i>
                </button>
                <div id="discount-slider" class="flex overflow-x-auto space-x-6 no-scrollbar snap-x snap-mandatory">
                    <?php foreach ($bestDeals as $product): ?>
                        <div class="product-card snap-start flex-shrink-0">
                            <img class="w-full h-48 object-contain p-3" src="<?= Html::encode($product['image']) ?>" alt="<?= Html::encode($product['name']) ?>" />
                            <div class="p-4 flex flex-col flex-grow">
                                <h4 class="text-lg font-semibold mb-2 break-words text-black"><?= Html::encode($product['name']) ?></h4>
                                <div class="flex items-center mt-auto">
                                    <?php if ($product['discount_percentage'] > 0): ?>
                                        <p class="text-gray-500 line-through mr-2 text-sm"><?= Html::encode($product['price']) ?> ₽</p>
                                        <p class="font-bold text-md text-[var(--primary-color)]"><?= Html::encode($product['price'] * (1 - $product['discount_percentage'] / 100)) ?> ₽</p>
                                    <?php else: ?>
                                        <p class="font-bold text-md text-[var(--text-color)]"><?= Html::encode($product['price']) ?> ₽</p>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= Url::to(['product/view', 'id' => $product['id']]) ?>" class="mt-3 block text-center bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-semibold py-2 px-4 rounded-xl transition duration-300 text-sm">Подробнее</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button id="scroll-right-discount" class="absolute -right-3 top-1/2 -translate-y-1/2 bg-[var(--block-bg)] p-3 rounded-full shadow-md z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-right text-[var(--primary-color)]"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Раздел новинок -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <h3 class="text-3xl font-semibold text-black mb-6 text-center drop-shadow-md">
                <i class="fas fa-watch mr-2 text-[var(--primary-color)]"></i> Новинки
            </h3>
            <div class="relative bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                <button id="scroll-left-new-arrivals" class="absolute -left-3 top-1/2 -translate-y-1/2 bg-[var(--block-bg)] p-3 rounded-full shadow-md z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-left text-[var(--primary-color)]"></i>
                </button>
                <div id="new-arrivals-slider" class="flex overflow-x-auto space-x-6 no-scrollbar snap-x snap-mandatory">
                    <?php foreach ($newArrivals as $product): ?>
                        <div class="product-card snap-start flex-shrink-0">
                            <img class="w-full h-48 object-contain p-3" src="<?= Html::encode($product['image']) ?>" alt="<?= Html::encode($product['name']) ?>" />
                            <div class="p-4 flex flex-col flex-grow">
                                <h4 class="text-lg font-semibold mb-2 break-words text-black"><?= Html::encode($product['name']) ?></h4>
                                <div class="flex items-center mt-auto">
                                    <?php if ($product['discount_percentage'] > 0): ?>
                                        <p class="text-gray-500 line-through mr-2 text-sm"><?= Html::encode($product['price']) ?> ₽</p>
                                        <p class="font-bold text-md text-[var(--primary-color)]"><?= Html::encode($product['price'] * (1 - $product['discount_percentage'] / 100)) ?> ₽</p>
                                    <?php else: ?>
                                        <p class="font-bold text-md text-[var(--text-color)]"><?= Html::encode($product['price']) ?> ₽</p>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= Url::to(['product/view', 'id' => $product['id']]) ?>" class="mt-3 block text-center bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-semibold py-2 px-4 rounded-xl transition duration-300 text-sm">Подробнее</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button id="scroll-right-new-arrivals" class="absolute -right-3 top-1/2 -translate-y-1/2 bg-[var(--block-bg)] p-3 rounded-full shadow-md z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-right text-[var(--primary-color)]"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Производители -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-semibold text-black mb-6 text-center drop-shadow-md">Наши производители</h2>
            <div class="relative  p-6">
                <button id="scroll-left" class="absolute -left-3 top-1/2 -translate-y-1/2 bg-[var(--block-bg)] p-3 rounded-full shadow-md z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-left text-[var(--primary-color)]"></i>
                </button>
                <div id="manufacturers-slider" class="flex overflow-x-auto space-x-8 no-scrollbar snap-x snap-mandatory">
                    <?php foreach ($manufacturers as $manufacturer): ?>
                        <div class="snap-start flex-shrink-0 w-40">
                            <a href="<?= Url::to(['site/brand', 'id' => $manufacturer['id']]) ?>" class="manufacturer-logo-container block">
                                <img class="w-full h-40 object-scale-down p-3" src="<?= Html::encode($manufacturer['logo']) ?>" alt="<?= Html::encode($manufacturer['name']) ?>" />
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button id="scroll-right" class="absolute -right-3 top-1/2 -translate-y-1/2 bg-[var(--block-bg)] p-3 rounded-full shadow-md z-10 opacity-70 hover:opacity-100 transition-opacity">
                    <i class="fas fa-chevron-right text-[var(--primary-color)]"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Рекомендации -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-semibold text-black mb-6 text-center drop-shadow-md">Рекомендуем вам</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-xl shadow-md bg-[var(--block-bg)] border border-[var(--border-color)] hover:shadow-lg transition-shadow">
                    <h3 class="text-xl font-semibold text-black mb-3"><i class="fas fa-shield-alt mr-2 text-[var(--primary-color)]"></i> Гарантия качества</h3>
                    <p class="text-[var(--text-color)] leading-relaxed text-sm">Все наши часы проходят строгий контроль качества для надежности.</p>
                </div>
                <div class="p-6 rounded-xl shadow-md bg-[var(--block-bg)] border border-[var(--border-color)] hover:shadow-lg transition-shadow">
                    <h3 class="text-xl font-semibold text-black mb-3"><i class="fas fa-shipping-fast mr-2 text-[var(--primary-color)]"></i> Бесплатная доставка</h3>
                    <p class="text-[var(--text-color)] leading-relaxed text-sm">Бесплатная доставка для заказов от 5000 ₽.</p>
                </div>
                <div class="p-6 rounded-xl shadow-md bg-[var(--block-bg)] border border-[var(--border-color)] hover:shadow-lg transition-shadow">
                    <h3 class="text-xl font-semibold text-black mb-3"><i class="fas fa-headset mr-2 text-[var(--primary-color)]"></i> Поддержка 24/7</h3>
                    <p class="text-[var(--text-color)] leading-relaxed text-sm">Наша поддержка всегда готова помочь вам.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    function setupSlider(sliderId, btnLeftId, btnRightId) {
        const slider = document.getElementById(sliderId);
        const btnLeft = document.getElementById(btnLeftId);
        const btnRight = document.getElementById(btnRightId);
        const scrollAmount = 300;

        function checkButtons() {
            btnLeft.style.opacity = slider.scrollLeft > 0 ? "1" : "0.7";
            btnRight.style.opacity = slider.scrollLeft + slider.clientWidth < slider.scrollWidth ? "1" : "0.7";
        }

        btnLeft.addEventListener("click", function () {
            slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
            setTimeout(checkButtons, 200);
        });

        btnRight.addEventListener("click", function () {
            slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
            setTimeout(checkButtons, 200);
        });

        slider.addEventListener("wheel", function (e) {
            if (e.deltaY !== 0) {
                e.preventDefault();
                slider.scrollBy({
                    left: e.deltaY > 0 ? scrollAmount : -scrollAmount,
                    behavior: "smooth"
                });
            }
        });

        checkButtons();
    }

    setupSlider("discount-slider", "scroll-left-discount", "scroll-right-discount");
    setupSlider("new-arrivals-slider", "scroll-left-new-arrivals", "scroll-right-new-arrivals");
    setupSlider("manufacturers-slider", "scroll-left", "scroll-right");
});
</script>