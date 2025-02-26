<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'О нас - Магазин смарт-часов';
?>

<div class="site-about">
    <!-- Hero Section -->
    <section class="bg-bg-color py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold text-primary-dark mb-8">О нашей компании</h1>
            <p class="text-xl text-text-color mb-8">
                Мы - ваш надежный партнер в мире современных смарт-часов. Узнайте больше о нашей истории, ценностях и команде.
            </p>
        </div>
    </section>
    <!-- Раздел "Наша история" -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
                <i class="fas fa-history mr-2"></i> Наша история
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                $historyItems = [
                    ['year' => '2020', 'event' => 'Основание', 'description' => 'Открытие первого магазина, ориентированного на широкий ассортимент и доступные цены.', 'highlight' => 'Основание'],
                    ['year' => '2021', 'event' => 'Расширение', 'description' => 'Добавление новых брендов и моделей, расширение географии продаж.', 'highlight' => 'Расширение'],
                    ['year' => '2022', 'event' => 'Онлайн-магазин', 'description' => 'Запуск интернет-магазина для удобства наших клиентов.', 'highlight' => 'Онлайн-магазин'],
                    ['year' => '2023', 'event' => 'Развитие', 'description' => 'Открытие новых филиалов, расширение ассортимента, улучшение сервиса.', 'highlight' => 'Развитие'],
                ];

                foreach ($historyItems as $item): ?>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="text-2xl font-bold text-gray-800 mt-0 mb-2" style="position: relative; padding-bottom: 5px;">
                            <?= $item['year'] ?>
                            <span style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background-color: #ddd;"></span>
                        </div>
                        <h4 class="text-base font-medium text-gray-700 mb-2">
                            <?php if (isset($item['highlight'])): ?>
                                <span class="font-semibold text-gray-800"><?= $item['event'] ?></span>
                            <?php else: ?>
                                <?= $item['event'] ?>
                            <?php endif; ?>
                        </h4>
                        <p class="text-gray-700"><?= $item['description'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Раздел "Наша команда" -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
                <i class="fas fa-users mr-2"></i> Наша команда сегодня
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-2xl text-gray-800 mb-2">2</h3>
                    <p class="text-gray-700">Филиала<br/>В двух городах.</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-2xl text-gray-800 mb-2">15</h3>
                    <p class="text-gray-700">Сотрудников<br/>В нашей дружной команде.</p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold text-2xl text-gray-800 mb-2">1000+</h3>
                    <p class="text-gray-700">Довольных клиентов<br/>Наши клиенты - наша гордость.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
            <i class="fas fa-user-circle mr-2"></i> Для клиентов
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Карточка 1 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Широкий ассортимент
                </h3>
                <p class="text-gray-700 text-center">
                    Мы предлагаем широкий выбор смарт-часов от ведущих мировых брендов.
                </p>
            </div>
            <!-- Карточка 2 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Выгодные цены
                </h3>
                <p class="text-gray-700 text-center">
                    У нас вы найдете выгодные цены и регулярные акции.
                </p>
            </div>
            <!-- Карточка 3 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Быстрая доставка
                </h3>
                <p class="text-gray-700 text-center">
                    Мы осуществляем быструю и удобную доставку.
                </p>
            </div>
            <!-- Карточка 4 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Квалифицированная поддержка
                </h3>
                <p class="text-gray-700 text-center">
                    Наши специалисты всегда готовы помочь с выбором и ответить на ваши вопросы.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- Раздел "Для сотрудников" -->
<section class="py-12 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
            <i class="fas fa-briefcase mr-2"></i> Для сотрудников
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Карточка 1 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Возможности для роста
                </h3>
                <p class="text-gray-700 text-center">
                    Мы предоставляем возможности для профессионального и карьерного роста.
                </p>
            </div>
            <!-- Карточка 2 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Дружный коллектив
                </h3>
                <p class="text-gray-700 text-center">
                    Мы ценим командную работу и дружелюбную атмосферу.
                </p>
            </div>
            <!-- Карточка 3 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Достойная оплата труда
                </h3>
                <p class="text-gray-700 text-center">
                    Мы предлагаем конкурентоспособную заработную плату.
                </p>
            </div>
            <!-- Карточка 4 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Гибкий график работы
                </h3>
                <p class="text-gray-700 text-center">
                    Возможны варианты гибкого графика работы.
                </p>
            </div>
        </div>
    </div>
</section>
<!-- Раздел "Для партнеров" -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
            <i class="fas fa-handshake mr-2"></i> Для партнеров
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- Карточка 1 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Взаимовыгодное сотрудничество
                </h3>
                <p class="text-gray-700 text-center">
                    Мы предлагаем выгодные условия сотрудничества.
                </p>
            </div>
            <!-- Карточка 2 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Надежность
                </h3>
                <p class="text-gray-700 text-center">
                    Мы являемся надежным партнером.
                </p>
            </div>
            <!-- Карточка 3 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Поддержка
                </h3>
                <p class="text-gray-700 text-center">
                    Мы оказываем всестороннюю поддержку нашим партнерам.
                </p>
            </div>
            <!-- Карточка 4 -->
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-300">
                <h3 class="text-xl font-semibold text-gray-800 text-center mb-2">
                    Индивидуальный подход
                </h3>
                <p class="text-gray-700 text-center">
                    Мы всегда стремимся найти индивидуальный подход к каждому партнеру.
                </p>
            </div>
        </div>
    </div>
</section>
    <!-- Раздел "Свяжитесь с нами" -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">
                <i class="fas fa-envelope mr-2"></i> Свяжитесь с нами
            </h2>
            <div class="text-center">
                <p class="text-gray-700 mb-4">Если у вас есть вопросы или предложения, мы всегда рады вам помочь!</p>
                <p class="text-gray-700">Email: <a href="mailto:info@smartwatchstore.com" class="text-blue-600 hover:text-blue-800">info@smartwatchstore.com</a></p>
                <p class="text-gray-700">Телефон: +7 (XXX) XXX-XX-XX</p>
            </div>
        </div>
    </section>

</div>