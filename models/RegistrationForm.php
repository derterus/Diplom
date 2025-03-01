<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegistrationForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_repeat'], 'required', 'message' => Yii::t('app', '{attribute} не может быть пустым.')],
            ['email', 'email', 'message' => Yii::t('app', 'Введите корректный email.')],
            ['username', 'string', 'min' => 3, 'max' => 20, 'tooShort' => Yii::t('app', 'Имя пользователя должно содержать минимум 3 символа.')],
            ['password', 'string', 'min' => 6, 'tooShort' => Yii::t('app', 'Пароль должен содержать минимум 6 символов.')],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Пароли должны совпадать.')],
        ];
    }
    


    public function attributeLabels()
{
    return [
        'username' => 'Имя пользователя',
        'email' => 'Email',
        'password' => 'Пароль',
        'password_repeat' => 'Подтверждение пароля',
    ];
}

}
