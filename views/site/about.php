<?php
/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'О нас - Магазин смарт-часов';
?>

<div class="site-about font-montserrat bg-[var(--bg-color)] text-[var(--text-color)] min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="mb-6">
            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 text-center shadow-md">
                <h1 class="text-4xl md:text-5xl font-bold text-black mb-4 drop-shadow-md">О нашей компании</h1>
                <p class="text-lg text-[var(--text-color)] max-w-2xl mx-auto leading-relaxed">Ваш надежный партнер в мире смарт-часов. Узнайте о нашей истории, ценностях и команде.</p>
            </div>
        </section>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: History Timeline -->
            <div class="lg:col-span-1">
                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md h-full">
                    <h2 class="text-2xl font-semibold text-black mb-4 text-center drop-shadow-md">
                        <i class="fas fa-history mr-2 text-[var(--primary-color)]"></i> Наша история
                    </h2>
                    <div class="space-y-4 relative before:absolute before:left-1/2 before:-translate-x-1/2 before:top-0 before:bottom-0 before:w-1 before:bg-[var(--primary-color)]">
                        <?php
                        $historyItems = [
                            ['year' => '2020', 'event' => 'Основание', 'description' => 'Открытие первого магазина с доступными ценами.'],
                            ['year' => '2021', 'event' => 'Расширение', 'description' => 'Новые бренды и регионы продаж.'],
                            ['year' => '2022', 'event' => 'Онлайн-магазин', 'description' => 'Запуск интернет-магазина для удобства.'],
                            ['year' => '2023', 'event' => 'Развитие', 'description' => 'Новые филиалы и улучшенный сервис.'],
                        ];
                        foreach ($historyItems as $index => $item): ?>
                            <div class="relative flex items-center justify-center">
                                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-3 w-full max-w-xs shadow-md z-10 <?= $index % 2 === 0 ? 'mr-auto' : 'ml-auto' ?>">
                                    <div class="text-lg font-bold text-[var(--primary-color)] mb-1"><?= Html::encode($item['year']) ?></div>
                                    <h4 class="text-base font-semibold text-black"><?= Html::encode($item['event']) ?></h4>
                                    <p class="text-sm text-[var(--text-color)]"><?= Html::encode($item['description']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Team + Tabs -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Team Stats -->
                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                    <h2 class="text-2xl font-semibold text-black mb-4 text-center drop-shadow-md">
                        <i class="fas fa-users mr-2 text-[var(--primary-color)]"></i> Наша команда
                    </h2>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <h3 class="font-semibold text-xl text-[var(--primary-color)] mb-2">2</h3>
                            <p class="text-base text-[var(--text-color)]">Филиала</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-xl text-[var(--primary-color)] mb-2">15</h3>
                            <p class="text-base text-[var(--text-color)]">Сотрудников</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-xl text-[var(--primary-color)] mb-2">1000+</h3>
                            <p class="text-base text-[var(--text-color)]">Клиентов</p>
                        </div>
                    </div>
                </div>

                <!-- Tabs: Clients, Employees, Partners -->
                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                    <div class="grid grid-cols-3 gap-2 mb-6">
                        <button class="tab-btn text-lg font-semibold text-black bg-gray-100 hover:bg-[var(--primary-color)] hover:text-white focus:outline-none transition-colors py-2 rounded-md active" data-tab="clients">Для клиентов</button>
                        <button class="tab-btn text-lg font-semibold text-black bg-gray-100 hover:bg-[var(--primary-color)] hover:text-white focus:outline-none transition-colors py-2 rounded-md" data-tab="employees">Для сотрудников</button>
                        <button class="tab-btn text-lg font-semibold text-black bg-gray-100 hover:bg-[var(--primary-color)] hover:text-white focus:outline-none transition-colors py-2 rounded-md" data-tab="partners">Для партнеров</button>
                    </div>
                    <div id="tab-content">
                        <!-- Для клиентов -->
                        <div id="clients" class="tab-pane grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Широкий ассортимент</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Смарт-часы от ведущих брендов мира.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Выгодные цены</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Конкурентные цены и регулярные акции.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Быстрая доставка</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Оперативная доставка по всей стране.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Поддержка 24/7</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Помощь в выборе и ответы на вопросы.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Гарантия качества</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Только сертифицированные товары.</p>
                            </div>
                        </div>
                        <!-- Для сотрудников -->
                        <div id="employees" class="tab-pane grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Возможности роста</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Карьерный и профессиональный рост.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Дружный коллектив</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Командная работа и поддержка.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Достойная зарплата</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Конкурентная оплата труда.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Гибкий график</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Удобные варианты работы.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Обучение</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Курсы и тренинги для развития.</p>
                            </div>
                        </div>
                        <!-- Для партнеров -->
                        <div id="partners" class="tab-pane grid grid-cols-1 md:grid-cols-3 gap-4 hidden">
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Сотрудничество</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Выгодные условия для бизнеса.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Надежность</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Стабильное партнерство.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Поддержка</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Всесторонняя помощь партнерам.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Индивидуальность</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Персональный подход к каждому.</p>
                            </div>
                            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-lg p-4 shadow-md hover:shadow-lg transition-shadow">
                                <h3 class="text-lg font-semibold text-black text-center mb-2">Ресурсы</h3>
                                <p class="text-base text-[var(--text-color)] text-center">Доступ к широкой сети продаж.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <section class="mt-6">
            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 text-center shadow-md">
                <h2 class="text-2xl font-semibold text-black mb-4 drop-shadow-md">
                    <i class="fas fa-envelope mr-2 text-[var(--primary-color)]"></i> Свяжитесь с нами
                </h2>
                <p class="text-base text-[var(--text-color)] mb-4">Есть вопросы или предложения? Мы всегда на связи!</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <p class="text-base text-[var(--text-color)]">Email: <a href="mailto:info@smartwatchstore.com" class="text-[var(--primary-color)] hover:text-[var(--primary-dark)] transition-colors">info@smartwatchstore.com</a></p>
                    <p class="text-base text-[var(--text-color)]">Телефон: <span class="font-semibold">+7 (123) 456-78-90</span></p>
                </div>
                <a href="mailto:info@smartwatchstore.com" class="inline-block bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white font-semibold px-6 py-3 rounded-xl transition duration-300 text-base shadow-md hover:shadow-lg">Написать нам</a>
            </div>
        </section>
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('bg-[var(--primary-color)]');
            b.classList.remove('text-white');
            b.classList.add('bg-gray-100');
        });
        btn.classList.add('active');
        btn.classList.add('bg-[var(--primary-color)]');
        btn.classList.add('text-white');
        btn.classList.remove('bg-gray-100');

        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
        document.getElementById(btn.dataset.tab).classList.remove('hidden');
    });
});
</script>