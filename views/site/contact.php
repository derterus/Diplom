<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\Html;
use yii\captcha\Captcha;
use yii\bootstrap5\ActiveForm;

$this->title = 'Контакты - Магазин смарт-часов';
?>

<div class="site-contact font-montserrat bg-[var(--bg-color)] text-[var(--text-color)] min-h-screen">
    <div class="container mx-auto px-4 py-10">
        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contact Info + Socials (Left Column) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                    <h2 class="text-3xl font-semibold text-black mb-4 text-center drop-shadow-md">
                        <i class="fas fa-address-card mr-2 text-[var(--primary-color)]"></i> Контакты
                    </h2>
                    <div class="space-y-4 text-center">
                        <h3 class="text-xl font-semibold text-black">Адрес</h3>
                        <p class="text-base text-[var(--text-color)]">г. Ангарск, ТК Центр, ул. Ленина, 1</p>
                        <p class="text-base"><i class="fas fa-phone-alt mr-2 text-[var(--primary-color)]"></i> +7 (123) 456-78-90</p>
                        <p class="text-base"><i class="fas fa-envelope mr-2 text-[var(--primary-color)]"></i> 
                            <a href="mailto:info@smartwatchstore.com" class="text-[var(--primary-color)] hover:text-[var(--primary-dark)] transition-colors">info@smartwatchstore.com</a>
                        </p>
                    </div>
                </div>
                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-semibold text-black mb-4 text-center">Соцсети</h3>
                    <div class="flex justify-center space-x-6">
                        <a href="https://facebook.com" class="text-blue-600 hover:text-blue-800 transition-colors"><i class="fab fa-facebook-square text-2xl"></i></a>
                        <a href="https://instagram.com" class="text-pink-600 hover:text-pink-800 transition-colors"><i class="fab fa-instagram text-2xl"></i></a>
                        <a href="https://twitter.com" class="text-blue-400 hover:text-blue-600 transition-colors"><i class="fab fa-twitter-square text-2xl"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form (Right Column) -->
            <div class="lg:col-span-2">
                <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                    <h2 class="text-3xl font-semibold text-black mb-6 text-center drop-shadow-md">
                        <i class="fas fa-envelope mr-2 text-[var(--primary-color)]"></i> Напишите нам
                    </h2>
                    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4 text-center">
                            <strong class="font-bold">Спасибо!</strong> Ваше сообщение отправлено.
                        </div>
                    <?php else: ?>
                        <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
                        <div class="space-y-4">
                            <?= $form->field($model, 'name', [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'block text-black text-sm font-semibold mb-1'],
                                'inputOptions' => [
                                    'class' => 'w-full px-4 py-3 text-base text-[var(--text-color)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]',
                                    'placeholder' => 'Ваше имя',
                                ],
                                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
                            ])->label('Имя') ?>

                            <?= $form->field($model, 'email', [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'block text-black text-sm font-semibold mb-1'],
                                'inputOptions' => [
                                    'class' => 'w-full px-4 py-3 text-base text-[var(--text-color)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]',
                                    'placeholder' => 'Ваш email',
                                ],
                                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
                            ])->input('email')->label('Email') ?>

                            <?= $form->field($model, 'subject', [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'block text-black text-sm font-semibold mb-1'],
                                'inputOptions' => [
                                    'class' => 'w-full px-4 py-3 text-base text-[var(--text-color)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]',
                                    'placeholder' => 'Тема сообщения',
                                ],
                                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
                            ])->label('Тема') ?>

                            <?= $form->field($model, 'body', [
                                'template' => "{label}\n{input}\n{error}",
                                'labelOptions' => ['class' => 'block text-black text-sm font-semibold mb-1'],
                                'inputOptions' => [
                                    'class' => 'w-full px-4 py-3 text-base text-[var(--text-color)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] resize-none',
                                    'placeholder' => 'Ваше сообщение',
                                ],
                                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
                            ])->textarea(['rows' => 4])->label('Сообщение') ?>

                            <?= $form->field($model, 'verifyCode', [
                                'template' => "{label}\n<div class='flex items-center gap-3'>{input}</div>\n{error}",
                                'labelOptions' => ['class' => 'block text-black text-sm font-semibold mb-1'],
                                'inputOptions' => [
                                    'class' => 'w-32 px-4 py-3 text-base text-[var(--text-color)] border border-[var(--border-color)] rounded-md focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)]',
                                ],
                                'errorOptions' => ['class' => 'text-red-500 text-xs mt-1'],
                            ])->widget(Captcha::class, [
                                'imageOptions' => ['class' => 'rounded-md border border-[var(--border-color)]'],
                            ])->label('Код проверки') ?>

                            <div>
                                <?= Html::submitButton('Отправить', [
                                    'class' => 'w-full bg-[var(--primary-color)] hover:bg-[var(--primary-dark)] text-white py-3 rounded-md text-base font-semibold transition duration-300 focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] shadow-md hover:shadow-lg',
                                ]) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-6">
            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-6 shadow-md">
                <h2 class="text-2xl font-semibold text-black mb-4 text-center drop-shadow-md">
                    <i class="fas fa-question-circle mr-2 text-[var(--primary-color)]"></i> Часто задаваемые вопросы
                </h2>
                <div class="space-y-4">
                    <?php
                    $faqItems = [
                        ['question' => 'Как сделать заказ?', 'answer' => 'Выберите товар, добавьте в корзину и оформите заказ с вашими данными.'],
                        ['question' => 'Как оплатить заказ?', 'answer' => 'Принимаем Visa, MasterCard, PayPal и банковский перевод.'],
                        ['question' => 'Как вернуть товар?', 'answer' => 'Возврат в течение 14 дней, если товар не использовался и сохранил вид.'],
                        ['question' => 'Доставка за пределы Ангарска?', 'answer' => 'Да, по всей России. Стоимость и сроки зависят от региона.'],
                    ];
                    foreach ($faqItems as $item): ?>
                        <div class="faq-item border-b border-[var(--border-color)] pb-3 last:border-b-0">
                            <button class="faq-question w-full text-left text-lg font-semibold text-black hover:text-[var(--primary-color)] focus:outline-none flex items-center transition-colors">
                                <i class="fas fa-caret-right mr-2 text-[var(--primary-color)] transition-transform duration-200"></i>
                                <?= Html::encode($item['question']) ?>
                            </button>
                            <div class="faq-answer text-base text-[var(--text-color)] mt-2 hidden"><?= Html::encode($item['answer']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="mt-6">
            <div class="bg-[var(--block-bg)] border border-[var(--border-color)] rounded-xl p-4 shadow-md">
                <h2 class="text-2xl font-semibold text-black mb-4 text-center drop-shadow-md">
                    <i class="fas fa-map-marker-alt mr-2 text-[var(--primary-color)]"></i> Где нас найти
                </h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d9706.551592362093!2d103.889020534486!3d52.539985367389285!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5d079d22e750be17%3A0xb6df942f6d187f2!2z0KLQoNCaICLQptC10L3RgtGAIg!5e0!3m2!1sru!2sru!4v1740664307963!5m2!1sru!2sru" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-md"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.faq-question').forEach((btn) => {
    btn.addEventListener('click', () => {
        const answer = btn.nextElementSibling;
        const icon = btn.querySelector('i');
        answer.classList.toggle('hidden');
        btn.classList.toggle('text-[var(--primary-color)]');
        icon.classList.toggle('fa-caret-right');
        icon.classList.toggle('fa-caret-down');
    });
});
</script>