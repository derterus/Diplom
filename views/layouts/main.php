<?php

/** @var \yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
$this->registerLinkTag(['rel' => 'preconnect', 'href' => 'https://fonts.googleapis.com']);
$this->registerLinkTag(['rel' => 'preconnect', 'href' => 'https://fonts.gstatic.com', 'crossorigin' => true]);
$this->registerLinkTag(['rel' => 'stylesheet', 'href' => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Open+Sans:wght@400;600&display=swap']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header" class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
    <div class="container mx-auto py-4 px-6 flex items-center justify-between">
        <!-- Левая часть (Лого и Название) -->
        <div class="flex items-center">
            <a href="<?= Yii::$app->homeUrl ?>" class="flex items-center text-xl font-semibold text-gray-800">
                <img src="" alt="Логотип" class="h-8 mr-2">
                <span class="ml-2">Смарт часы</span>
            </a>
        </div>

        <!-- Правая часть (Меню) -->
        <div>
            <?php
            echo Nav::widget([
                'options' => ['class' => 'flex space-x-4'],
                'items' => [
                    ['label' => 'Каталог', 'url' => ['/site/catalog'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']],
                    ['label' => 'О нас', 'url' => ['/site/about'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']],
                    ['label' => 'Контакты', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']],
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Вход', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']]
                    ) : (
                        [
                            'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => [
                                'class' => 'text-gray-700 hover:text-gray-900',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ],
                        ]
                    ),
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Регистрация', 'url' => ['/site/signup'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']]
                    ) : (
                        ['label' => 'Профиль', 'url' => ['/profile/index'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']] // Замените '/profile/index' на актуальный URL профиля
                    ),
                    ['label' => $this->render('_cart_icon'), 'url' => ['/cart/index'], 'linkOptions' => ['class' => 'text-gray-700 hover:text-gray-900']],


                ],
            ]);
            ?>
        </div>
    </div>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container mx-auto px-4 pt-20">  <!--  Добавлен pt-20 для отступа -->
        <?= Breadcrumbs::widget([
            'links' => !empty($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="bg-gray-800 py-6">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Адрес и время работы -->
            <div>
                <h4 class="font-semibold text-white mb-2">Информация</h4>
                <p class="text-gray-300">Адрес: ул. Примерная, д. 123</p>
                <p class="text-gray-300">Время работы: Пн-Пт, 9:00 - 18:00</p>
            </div>

            <!-- Скачать приложение -->
            <div>
                <h4 class="font-semibold text-white mb-2">Скачать приложение</h4>
                <div class="flex space-x-2">
                    <a href="#" target="_blank">
                        <img src="" alt="App Store" class="h-8">
                    </a>
                    <a href="#" target="_blank">
                        <img src="" alt="Google Play" class="h-8">
                    </a>
                </div>
            </div>

            <!-- Телефон связи -->
            <div>
                <h4 class="font-semibold text-white mb-2">Контакты</h4>
                <p class="text-gray-300">Телефон: +7 (123) 456-78-90</p>
                <p class="text-gray-300">Email: info@example.com</p>
            </div>

            <!-- Методы оплаты -->
            <div>
                <h4 class="font-semibold text-white mb-2">Методы оплаты</h4>
                <div class="flex space-x-2">
                    <img src="" alt="Visa" class="h-6">
                    <img src="" alt="Mastercard" class="h-6">
                    <img src="" alt="PayPal" class="h-6">
                    <img src="" alt="Apple Pay" class="h-6">
                </div>
            </div>
        </div>

        <!-- Нижняя часть подвала -->
        <div class="mt-8 pt-4 border-t border-gray-700 text-center text-gray-300">
            <p>&copy; 2025 Компания Умные часы</p>
            <p>Администрация Сайта не несет ответственности за размещаемые Пользователями материалы (в т.ч. информацию и изображения), их содержание и качество.</p>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>