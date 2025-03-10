<?php
/** @var \yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;

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
    <style>
        .header-hidden {
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            margin-top: 10px;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        @media (max-width: 768px) {
            .menu {
                display: none;
                flex-direction: column; /* Уже есть, оставляем */
                background-color: #fff;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                padding: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 999;
            }
            .menu.active {
                display: flex;
            }
            .menu .nav { /* Добавляем стили для nav внутри меню */
                flex-direction: column !important;
                width: 100%;
                gap: 10px !important; /* Уменьшаем промежуток между элементами */
            }
            .menu .nav-item {
                width: 100%;
                text-align: left; /* Выравнивание текста по левому краю */
            }
            .menu .nav-link {
                display: block;
                padding: 10px 0; /* Увеличиваем область клика */
                width: 100%;
            }
            .menu .dropdown-content {
                position: static; /* Для мобильной версии убираем absolute */
                width: 100%;
                box-shadow: none; /* Убираем тень для вложенного меню */
                margin-top: 0;
                padding-left: 20px; /* Отступ для вложенных элементов */
            }
            .burger-icon {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                width: 30px;
                height: 20px;
                cursor: pointer;
            }
            .burger-icon div {
                height: 4px;
                background-color: #ea580c;
                border-radius: 4px;
            }
        }
        @media (min-width: 769px) {
            .menu {
                display: flex;
                gap: 20px;
            }
            .burger-icon {
                display: none;
            }
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header" class="fixed top-0 left-0 w-full bg-white shadow-md z-50">
    <div class="max-w-7xl mx-auto py-3 px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?= Yii::$app->homeUrl ?>" class="flex items-center">
                <img src="<?= Yii::$app->homeUrl ?>/uploads/logo.png" 
                     alt="Логотип" 
                     class="h-12 w-auto max-h-14 md:h-16 md:max-h-20 object-contain"> 
                <!-- Логотип теперь масштабируется корректно -->
            </a>
        </div>
        <div class="burger-icon" id="burger-icon" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div id="menu" class="menu">
            <?php
            echo Nav::widget([
                'options' => ['class' => 'flex items-center gap-6'],
                'items' => [
                    ['label' => 'Каталог', 'url' => ['/site/catalog'], 'linkOptions' => ['class' => 'text-text-color hover:text-primary-dark transition-colors']],
                    ['label' => 'О нас', 'url' => ['/site/about'], 'linkOptions' => ['class' => 'text-text-color hover:text-primary-dark transition-colors']],
                    ['label' => 'Контакты', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'text-text-color hover:text-primary-dark transition-colors']],
                    ['label' => '<i class="fas fa-shopping-cart"></i>', 'url' => ['/cart/index'], 'encode' => false, 'linkOptions' => ['class' => 'text-text-color hover:text-primary-dark transition-colors']],
                    Yii::$app->user->isGuest ? (
                        ['label' => 'Вход', 'url' => ['/site/login'], 'linkOptions' => ['class' => 'text-text-color hover:text-primary-dark transition-colors']]
                    ) : (
                        [
                            'label' => '<i class="fas fa-user"></i>',
                            'encode' => false,
                            'linkOptions' => ['class' => 'text-text-color hover:text-primary-dark transition-colors dropdown'],
                            'items' => [
                                ['label' => 'Профиль', 'url' => ['/profile/index']],
                                ['label' => 'Выход', 'url' => ['/site/logout'], 'linkOptions' => ['data' => ['method' => 'post']]],
                            ],
                        ]
                    ),
                ],
            ]);
            ?>
        </div>
    </div>
</header>


<main id="main" class="flex-shrink-0" role="main">
    <div class="max-w-7xl mx-auto px-4 pt-20"> <!-- Отступ от шапки -->
        <?= Breadcrumbs::widget(['links' => !empty($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="bg-gray-800 py-6 mt-auto">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <h4 class="font-semibold text-white mb-2">Информация</h4>
                <p class="text-gray-300">Адрес: г. Ангарск, ТК Центр, ул. Ленина, 1</p>
                <p class="text-gray-300">Время работы: Пн-Пт, 9:00 - 18:00</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-2">Скачать приложение</h4>
                <div class="flex space-x-2">
                    <a href="https://play.google.com/store" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/78/Google_Play_Store_badge_EN.svg/192px-Google_Play_Store_badge_EN.svg.png" 
                             alt="Google Play" class="h-8">
                    </a>
                    <a href="https://appgallery.huawei.com/" target="_blank">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9e/Huawei_AppGallery_Badge_Black_EN.svg" 
                             alt="Huawei AppGallery" class="h-8">
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-2">Контакты</h4>
                <p class="text-gray-300">Телефон: +7 (123) 456-78-90</p>
                <p class="text-gray-300">Email: info@smartwatchstore.com</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-2">Методы оплаты</h4>
                <div class="flex space-x-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/192px-Visa_Inc._logo.svg.png" 
                         alt="Visa" class="h-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/192px-Mastercard-logo.svg.png" 
                         alt="Mastercard" class="h-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/PayPal.svg/192px-PayPal.svg.png" 
                         alt="PayPal" class="h-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Nspk-mir.png" 
                         alt="Google Pay" class="h-6">
                </div>
            </div>
        </div>
        <div class="mt-8 pt-4 border-t border-gray-700 text-center text-gray-300">
            <p>&copy; 2025 Компания Умные часы</p>
            <p>Администрация сайта не несет ответственности за размещаемые пользователями материалы.</p>
        </div>
    </div>
</footer>



<?php $this->endBody() ?>

<script>
function toggleMenu() {
    const menu = document.getElementById('menu');
    menu.classList.toggle('active');
}

let lastScrollTop = 0;
const header = document.getElementById('header');
window.addEventListener('scroll', function() {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop) {
        header.classList.add('header-hidden');
    } else {
        header.classList.remove('header-hidden');
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
});
</script>
</body>
</html>
<?php $this->endPage() ?>