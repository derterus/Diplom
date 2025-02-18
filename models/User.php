<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int|null $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $access_token
 * @property string|null $role
 *
 * @property Orders[] $orders
 * @property Reviews[] $reviews
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return 'user';
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['role'] = 'role';
        return $fields;
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['username', 'password', 'email'], 'required'],
            [['password'], 'string'],
            [['status'], 'integer'],
            [['username', 'password_reset_token', 'email', 'access_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 16],
            [['role'], 'string', 'max' => 20],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'access_token' => 'Access Token',
            'role' => 'Role',
        ];
    }

    public static function findIdentity($id)
    {
        $user = static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
        Yii::debug('Loaded user with role: ' . ($user ? $user->role : 'null'), 'auth');
        return $user;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
        return $user;
    }

    public static function findByUsername($username)
    {
        $query = static::find()->where(['username' => $username, 'status' => self::STATUS_ACTIVE])->cache(false);
        Yii::debug('SQL Query: ' . $query->createCommand()->getRawSql(), 'db');
        $user = $query->one();
        Yii::debug('Loaded user with role: ' . ($user ? $user->role : 'null'), 'auth');
        return $user;
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        if (empty($this->password_hash)) {
            return false;
        }
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $this->auth_key = substr(str_shuffle($characters), 0, 16);
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomKey() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generateAccessToken()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $this->access_token = substr(str_shuffle($characters), 0, 255);
    }

    public function getRole()
    {
        return $this->role;
    }

    public function beforeSave($insert)
    {
        // Убедимся, что роль установлена перед сохранением
        Yii::debug('Role before save: ' . $this->role, 'auth');
        Yii::debug('Attributes before save: ' . json_encode($this->attributes), 'auth');
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::debug('Role in afterSave: ' . $this->role, 'auth');
        Yii::debug('Attributes after save: ' . json_encode($this->attributes), 'auth');
    }

    public function afterFind()
    {
        parent::afterFind();
        Yii::debug('Role in afterFind: ' . $this->role, 'auth');
    }

    public function getOrders()
    {
        return $this->hasMany(Orders::class, ['user_id' => 'id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['user_id' => 'id']);
    }
}

