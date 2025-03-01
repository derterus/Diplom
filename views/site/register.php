<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Регистрация';
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 60vh; margin: 0; padding: 0;">
    <div class="card shadow-lg p-4 rounded" style="width: 400px;">
        <h2 class="mb-4 text-center">Регистрация</h2>
        
        <div id="register-form-container">
            <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

            <div class="mb-3">
                <?= $form->field($model, 'username')->textInput([
                    'id' => 'username',
                    'placeholder' => 'Введите имя пользователя',
                    'class' => 'form-control'
                ])->label(false) ?>
                <div class="invalid-feedback">Необходимо заполнить «Имя пользователя».</div>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'email')->textInput([
                    'id' => 'email',
                    'type' => 'email',
                    'placeholder' => 'Введите email',
                    'class' => 'form-control'
                ])->label(false) ?>
                <div class="invalid-feedback">Введите корректный email.</div>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'password')->passwordInput([
                    'id' => 'password',
                    'placeholder' => 'Введите пароль',
                    'class' => 'form-control'
                ])->label(false) ?>
                <div class="invalid-feedback">Пароль должен быть не менее 6 символов.</div>
            </div>

            <div class="mb-3">
                <?= $form->field($model, 'password_repeat')->passwordInput([
                    'id' => 'password_repeat',
                    'placeholder' => 'Повторите пароль',
                    'class' => 'form-control'
                ])->label(false) ?>
                <div class="invalid-feedback">Пароли не совпадают.</div>
            </div>

            <div class="text-center mt-3">
                <?= Html::button('Зарегистрироваться', ['class' => 'btn btn-primary w-100', 'id' => 'register-button']) ?>
            </div>

            <p class="text-center mt-3">
                Уже есть аккаунт? <a href="<?= Url::to(['site/login']) ?>">Войти</a>
            </p>

            <?php ActiveForm::end(); ?>
        </div>

        <div id="register-success" class="alert alert-success text-center mt-3" style="display: none;">
            Регистрация успешна! Теперь вы можете <a href="<?= Url::to(['site/login']) ?>">войти</a>.
        </div>

        <div id="register-error" class="alert alert-danger text-center mt-3" style="display: none;">
            <p id="error-message"></p>
        </div>
    </div>
</div>

<script>
document.getElementById('register-button').addEventListener('click', function () {
    let username = document.getElementById('username');
    let email = document.getElementById('email');
    let password = document.getElementById('password');
    let passwordRepeat = document.getElementById('password_repeat');
    let isValid = true;

    // Очистка прошлых ошибок
    [username, email, password, passwordRepeat].forEach(input => {
        input.classList.remove('is-invalid');
    });

    // Проверка username
    if (username.value.trim().length < 3) {
        username.classList.add('is-invalid');
        isValid = false;
    }

    // Проверка email
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        email.classList.add('is-invalid');
        isValid = false;
    }

    // Проверка пароля
    if (password.value.length < 6) {
        password.classList.add('is-invalid');
        isValid = false;
    }

    // Проверка повторного пароля
    if (password.value !== passwordRepeat.value || passwordRepeat.value === '') {
        passwordRepeat.classList.add('is-invalid');
        isValid = false;
    }

    // Если есть ошибки, отменяем отправку запроса
    if (!isValid) return;

    // Отключаем кнопку, чтобы предотвратить повторную отправку
    let registerButton = document.getElementById('register-button');
    registerButton.disabled = true;
    registerButton.innerText = 'Загрузка...';

    let formData = {
        username: username.value.trim(),
        email: email.value.trim(),
        password: password.value
    };

    fetch('http://localhost/basic/web/registration/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        // Включаем кнопку обратно
        registerButton.disabled = false;
        registerButton.innerText = 'Зарегистрироваться';
        // Проверяем, есть ли ошибка в данных
        if (data.access_token) {
            // Если регистрация прошла успешно
            document.getElementById('register-form-container').style.display = 'none';
            document.getElementById('register-success').style.display = 'block';
            document.getElementById('register-error').style.display = 'none'; // скрываем ошибку, если она была
        } else {
            // Обработка ошибок, если они есть
            let errorMessage = '';
            try {
                // Пытаемся разобрать строку ошибки
                let errorDetails = JSON.parse(data.message);
                if (errorDetails.username) {
                    errorMessage += 'Имя пользователя уже занято. ';
                    document.getElementById('username').classList.add('is-invalid');
                }
                if (errorDetails.email) {
                    errorMessage += 'Email уже занят. ';
                    document.getElementById('email').classList.add('is-invalid');
                }
            } catch (e) {
                errorMessage = 'Произошла ошибка при регистрации. Попробуйте еще раз.';
            }
            // Выводим ошибку на экран
            document.getElementById('error-message').innerText = errorMessage || 'Произошла ошибка при регистрации. Попробуйте еще раз.';
            document.getElementById('register-error').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        document.getElementById('register-error').style.display = 'block';
        // Включаем кнопку обратно
        registerButton.disabled = false;
        registerButton.innerText = 'Зарегистрироваться';
    });
});
</script>
