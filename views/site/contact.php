<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\captcha\Captcha;
use yii\bootstrap5\ActiveForm;

$this->title = 'Контакты - Магазин смарт-часов';
?>
<div class="site-contact">

    <!-- Contact Information (without background) -->
    <section class="py-12">
        <div class="container mx-auto text-center px-4">
            <h2 class="text-3xl font-semibold text-primary-dark mb-6">Контактная информация</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Address -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-semibold text-primary-dark">Наш адрес</h3>
                    <p class="text-lg text-gray-700">г. Ангарск, ТК Центр города, ул. Ленина, д. 1</p>
                    <div class="text-lg">
                        <i class="fas fa-phone-alt text-primary-dark"></i> <span>+7 (123) 456-78-90</span><br>
                        <i class="fas fa-envelope text-primary-dark"></i> <a href="mailto:info@smartwatchstore.com" class="text-blue-600">info@smartwatchstore.com</a>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="space-y-4">
                    <h3 class="text-2xl font-semibold text-primary-dark">Мы в соцсетях</h3>
                    <div class="flex justify-center space-x-6">
                        <a href="https://facebook.com" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook-square text-3xl"></i></a>
                        <a href="https://instagram.com" class="text-pink-600 hover:text-pink-800"><i class="fab fa-instagram text-3xl"></i></a>
                        <a href="https://twitter.com" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter-square text-3xl"></i></a>
                    </div>
                </div>

            </div>
        </div>
    </section>

<!-- Contact Form Section -->
<section id="contact-form" class="py-12">
    <div class="container mx-auto px-6 max-w-2xl">
        <h2 class="text-3xl font-semibold text-primary-dark mb-6 text-center">Напишите нам</h2>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Спасибо!</strong>
                <span class="block sm:inline">Ваше сообщение успешно отправлено. Мы свяжемся с вами в ближайшее время.</span>
            </div>
        <?php else: ?>
            <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-8"> <!-- Здесь убрали bg-white от секции -->
                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <div class="mb-4">
                    <?= $form->field($model, 'name', [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'block text-gray-700 text-sm font-bold mb-2', 'label' => 'Ваше имя'],
                        'inputOptions' => [
                            'class' => 'block w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary',
                            'placeholder' => 'Ваше имя',
                        ],
                        'errorOptions' => ['class' => 'text-red-500 text-xs italic mt-1']
                    ]) ?>
                </div>

                <div class="mb-4">
                    <?= $form->field($model, 'email', [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'block text-gray-700 text-sm font-bold mb-2', 'label' => 'Ваш email'],
                        'inputOptions' => [
                            'class' => 'block w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary',
                            'placeholder' => 'Ваш email',
                        ],
                        'errorOptions' => ['class' => 'text-red-500 text-xs italic mt-1']
                    ])->input('email') ?>
                </div>

                <div class="mb-4">
                    <?= $form->field($model, 'subject', [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'block text-gray-700 text-sm font-bold mb-2', 'label' => 'Тема сообщения'],
                        'inputOptions' => [
                            'class' => 'block w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary',
                            'placeholder' => 'Тема сообщения',
                        ],
                        'errorOptions' => ['class' => 'text-red-500 text-xs italic mt-1']
                    ]) ?>
                </div>

                <div class="mb-6">
                    <?= $form->field($model, 'body', [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'block text-gray-700 text-sm font-bold mb-2', 'label' => 'Текст сообщения'],
                        'inputOptions' => [
                            'class' => 'block w-full px-4 py-3 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary resize-none',
                            'placeholder' => 'Опишите свой вопрос или проблему',
                        ],
                        'errorOptions' => ['class' => 'text-red-500 text-xs italic mt-1']
                    ])->textarea(['rows' => 6]) ?>
                </div>

                <div class="mb-6">
                    <?= $form->field($model, 'verifyCode', [
                        'template' => "{label}\n<div class='flex items-center'>{input}</div>\n{error}",  // Corrected template
                        'labelOptions' => ['class' => 'block text-gray-700 text-sm font-bold', 'label' => 'Код проверки'],
                        'inputOptions' => ['class' => 'shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline w-32 ml-2'], // Adjust width as needed
                        'errorOptions' => ['class' => 'text-red-500 text-xs italic mt-1'],
                    ])->widget(Captcha::class, [
                        'imageOptions' => ['class' => 'rounded-md border'], // Улучшаем внешний вид изображения
                    ]) ?>
                </div>

                <div>
    <?= Html::submitButton('Отправить сообщение', [
        'class' => 'bg-orange-500 hover:bg-orange-600 text-white w-full py-3 rounded-md text-lg font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-orange-400 shadow-lg hover:shadow-xl'
    ]) ?>
</div>



                <?php ActiveForm::end(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
    <!-- FAQ Section -->
    <section id="faq" class="py-12 bg-white">
        <div class="container mx-auto px-6">
            <h2 id="faq-title" class="text-3xl font-semibold text-primary-dark mb-6 text-center cursor-pointer">
                Часто задаваемые вопросы
            </h2>
            <div id="faq-list" class="space-y-4">
                <?php
                $faqItems = [
                    ['question' => 'Как сделать заказ?', 'answer' => 'Чтобы сделать заказ, выберите товар и добавьте его в корзину. После этого перейдите к оформлению заказа, укажите ваши контактные данные и выберите способ оплаты.'],
                    ['question' => 'Как оплатить заказ?', 'answer' => 'Мы принимаем оплату картами Visa, MasterCard, а также через PayPal и банковский перевод.'],
                    ['question' => 'Как вернуть товар?', 'answer' => 'Вы можете вернуть товар в течение 14 дней с момента получения, если он не был в использовании и сохранил свой товарный вид.'],
                    ['question' => 'Есть ли доставка за пределы Ангарска?', 'answer' => 'Да, мы доставляем по всей России. Стоимость и сроки доставки зависят от вашего региона.'],
                ];

                foreach ($faqItems as $item): ?>
                    <div class="faq-item border-b pb-4">
                        <button class="faq-question w-full text-left text-xl font-semibold text-primary-dark focus:outline-none hover:text-primary flex items-center">
                            <i class="fas fa-question-circle mr-2 text-primary-dark"></i>
                            <?= Html::encode($item['question']) ?>
                        </button>
                        <div class="faq-answer text-lg text-gray-700 mt-2 hidden">
                            <?= Html::encode($item['answer']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Map Section (without background and larger size) -->
    <section class="py-12">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-semibold text-primary-dark mb-6">
                <i class="fas fa-map-marker-alt mr-2"></i> Где нас найти
            </h2>
            <div class="relative w-full lg:h-[600px] h-[400px]"> <!-- Adjust map size for larger screens -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9706.551592362093!2d103.889020534486!3d52.539985367389285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5d079d22e750be17%3A0xb6df942f6d187f2!2z0KLQoNCaICLQptC10L3RgtGAIg!5e0!3m2!1sru!2sru!4v1740664307963!5m2!1sru!2sru" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

</div>

<script>
    // Toggle FAQ answers
    document.querySelectorAll('.faq-question').forEach((btn) => {
        btn.addEventListener('click', () => {
            const answer = btn.nextElementSibling;
            answer.classList.toggle('hidden');
        });
    });
</script>
