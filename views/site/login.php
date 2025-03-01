<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Вход';
?>
<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh; margin: 0; padding: 0;">
    <div class="card shadow p-3" style="width: 100%; max-width: 350px;">
        <h3 class="text-center mb-3" style="font-size: 1.5rem;"><?= Html::encode($this->title) ?></h3>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username')->textInput([
            'autofocus' => true, 
            'placeholder' => 'Логин', 
            'class' => 'form-control form-control-sm'
        ])->label(false) ?>

        <?= $form->field($model, 'password')->passwordInput([
            'placeholder' => 'Пароль', 
            'class' => 'form-control form-control-sm'
        ])->label(false) ?>

        <div class="form-check mb-3">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'class' => 'form-check-input', 
                'style' => 'transform: scale(0.8);'
            ])->label('Запомнить меня', ['class' => 'form-check-label']) ?>
        </div>

        <div class="d-grid">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-sm']) ?>
        </div>

        <div class="text-center mt-2" style="font-size: 0.85rem;">
            <p class="mb-1"><?= Html::a('Забыли пароль?', ['site/request-password-reset']) ?></p>
            <p>Нет аккаунта? <?= Html::a('Регистрация', ['site/register']) ?></p>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
