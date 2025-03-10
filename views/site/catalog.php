<?php
/* @var $this yii\web\View */
/* @var $products array */
/* @var $newArrivals array */
/* @var $bestDeals array */

use yii\helpers\Html;
use yii\helpers\Url;

$baseUrl = Yii::$app->request->baseUrl;
$this->registerJsVar('baseUrl', $baseUrl);
?>

<div class="py-6 ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <button id="show-filters-button" class="lg:hidden bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold py-1 px-3 rounded-lg mb-2 shadow-md hover:shadow-lg transition-shadow">Фильтры</button>
        <div class="lg:flex gap-4">
            <!-- Filters Section -->
            <div id="filters-section" class="lg:w-1/4 mb-4 lg:mb-0 hidden lg:block">
                <div class="bg-white rounded-xl shadow-lg p-4 js-filters border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Фильтры</h3>
                    <form id="filter-form" class="space-y-3">
                        <?php foreach ($characteristics as $char): ?>
                            <div>
                                <h4 class="font-medium text-gray-700 text-sm mb-1"><?= Html::encode($char['name']) ?></h4>
                                <div class="space-y-1 max-h-40 overflow-y-auto">
                                    <?php foreach ($char['values'] as $value): ?>
                                        <label class="flex items-center text-sm text-gray-600 hover:bg-gray-50 p-1 rounded">
                                            <input type="checkbox" name="filters[<?= Html::encode($char['id']) ?>][]" value="<?= Html::encode($value['value']) ?>" class="form-checkbox h-4 w-4 text-orange-500 focus:ring-orange-300 rounded">
                                            <span class="ml-2"><?= Html::encode($value['value']) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200 hover:from-orange-600 hover:to-orange-700 shadow-md">Применить</button>
                    </form>
                </div>
            </div>

            <!-- Products Section -->
            <div class="lg:w-3/4">
                <h2 class="text-xl font-semibold text-gray-800 mb-2">Смарт-часы</h2>
                <div id="products-list" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full border border-gray-200">
                            <a href="<?= Html::encode($baseUrl . '/web-product/view?id=' . $product['id']) ?>" class="block flex-grow">
                                <div class="relative">
                                    <img src="<?= Html::encode($product['image']) ?>" alt="<?= Html::encode($product['name']) ?>" class="w-full h-48 object-contain p-2">
                                    <?php if (isset($product['discount_percentage']) && $product['discount_percentage'] > 0): ?>
                                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow"><?= Html::encode('-' . $product['discount_percentage'] . '%') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3 flex flex-col justify-between flex-grow">
                                    <div>
                                        <h3 class="text-base font-semibold text-gray-800 mb-1 truncate"><?= Html::encode($product['name']) ?></h3>
                                        <p class="text-xs text-gray-600 mb-1 line-clamp-2"><?= Html::encode($product['description']) ?></p>
                                        <div class="flex items-center mb-1">
                                            <?= str_repeat('<svg class="w-4 h-4 fill-current text-yellow-500"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 5.19 8.63 0 9.24l5.46 4.73L5.82 21z"/></svg>', 5) ?>
                                            <span class="text-gray-500 text-xs ml-1">(5.0)</span>
                                        </div>
                                    </div>
                                    <?php
                                    $originalPrice = Html::encode($product['price']);
                                    $discountPercentage = isset($product['discount_percentage']) ? Html::encode($product['discount_percentage']) : 0;
                                    $discountAmount = $originalPrice * ($discountPercentage / 100);
                                    $newPrice = $originalPrice - $discountAmount;
                                    ?>
                                    <div class="flex items-center justify-between">
                                        <?php if ($discountPercentage > 0): ?>
                                            <div>
                                                <span class="text-gray-500 line-through text-sm"><?= $originalPrice ?> ₽</span>
                                                <span class="text-lg font-bold text-orange-600 ml-1"><?= number_format($newPrice, 0, '.', '') ?> ₽</span>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-lg font-bold text-orange-600"><?= $originalPrice ?> ₽</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                            <div class="px-3 pb-3">
                                <a href="<?= Url::to(['cart/add', 'id' => $product['id']]) ?>" class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition-all duration-200 shadow-md text-sm">Купить</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p id="no-products" class="text-center text-gray-500 mt-4 hidden">Нет товаров</p>
                <p id="loading-error" class="text-center text-red-500 mt-4 hidden">Ошибка загрузки</p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showFiltersButton = document.getElementById('show-filters-button');
        const filtersSection = document.getElementById('filters-section');
        showFiltersButton.addEventListener('click', () => filtersSection.classList.toggle('hidden'));

        const filterForm = document.getElementById('filter-form');
        const productsList = document.getElementById('products-list');
        const noProductsMessage = document.getElementById('no-products');
        const loadingErrorMessage = document.getElementById('loading-error');

        filterForm.addEventListener('submit', function(event) {
            event.preventDefault();
            productsList.innerHTML = '<p class="text-center text-gray-500">Загрузка...</p>';
            noProductsMessage.classList.add('hidden');
            loadingErrorMessage.classList.add('hidden');

            let formData = new FormData(this);
            let filters = {};
            formData.forEach((value, key) => {
                if (!filters[key]) filters[key] = [];
                filters[key].push(value);
            });

            let queryString = new URLSearchParams({ filters: JSON.stringify(filters) }).toString();

            fetch(baseUrl + '/filter?' + queryString)
                .then(response => response.json())
                .then(data => {
                    if (data.products && Array.isArray(data.products)) {
                        if (data.products.length === 0) {
                            productsList.innerHTML = '';
                            noProductsMessage.classList.remove('hidden');
                        } else {
                            noProductsMessage.classList.add('hidden');
                            productsList.innerHTML = data.products.map(product => {
                                const originalPrice = parseFloat(product.price);
                                const discountPercentage = parseFloat(product.discount_percentage) || 0;
                                const discountAmount = originalPrice * (discountPercentage / 100);
                                const newPrice = originalPrice - discountAmount;

                                return `
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full border border-gray-200">
                                        <a href="${baseUrl}/web-product/view?id=${product.id}" class="block flex-grow">
                                            <div class="relative">
                                                <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-contain p-2">
                                                ${discountPercentage > 0 ? `<div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow">-${discountPercentage}%</div>` : ''}
                                            </div>
                                            <div class="p-3 flex flex-col justify-between flex-grow">
                                                <div>
                                                    <h3 class="text-base font-semibold text-gray-800 mb-1 truncate">${product.name}</h3>
                                                    <p class="text-xs text-gray-600 mb-1 line-clamp-2">${product.description}</p>
                                                    <div class="flex items-center mb-1">
                                                        ${'<svg class="w-4 h-4 fill-current text-yellow-500"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 5.19 8.63 0 9.24l5.46 4.73L5.82 21z"/></svg>'.repeat(5)}
                                                        <span class="text-gray-500 text-xs ml-1">(5.0)</span>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    ${discountPercentage > 0 ? `
                                                        <div>
                                                            <span class="text-gray-500 line-through text-sm">${originalPrice.toFixed(0)} ₽</span>
                                                            <span class="text-lg font-bold text-orange-600 ml-1">${newPrice.toFixed(0)} ₽</span>
                                                        </div>
                                                    ` : `<p class="text-lg font-bold text-orange-600">${originalPrice.toFixed(0)} ₽</p>`}
                                                </div>
                                            </div>
                                        </a>
                                        <div class="px-3 pb-3">
                                            <a href="${baseUrl}/cart/add?id=${product.id}" class="block w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition-all duration-200 shadow-md text-sm">Купить</a>
                                        </div>
                                    </div>
                                `;
                            }).join('');
                        }
                    } else {
                        productsList.innerHTML = '';
                        loadingErrorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    productsList.innerHTML = '';
                    loadingErrorMessage.classList.remove('hidden');
                    console.error('Error:', error);
                })
                .finally(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
        });

        const productDescriptions = document.querySelectorAll('.product-description');
        productDescriptions.forEach(desc => {
            const maxLength = 80;
            if (desc.textContent.length > maxLength) {
                desc.textContent = desc.textContent.slice(0, maxLength) + '...';
            }
        });
    });
</script>

<style>
    .js-filters {
        max-height: 80vh;
        overflow-y: auto;
        position: sticky;
        top: 10px;
    }
    .product-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .product-description {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>