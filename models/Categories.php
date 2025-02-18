<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\db\Expression;

class Categories extends ActiveRecord
{
    public static function tableName()
    {
        return 'categories';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'), // Используем текущую дату и время в формате MySQL
            ],
        ];
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description', 'meta_keywords', 'meta_description'], 'string'],
            [['parent_id'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['parent_id' => 'id']],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, pdf', 'maxSize' => 2*1024*1024],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Description',
            'image' => 'Image',
            'parent_id' => 'Parent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getParent()
    {
        return $this->hasOne(Categories::class, ['id' => 'parent_id']);
    }

    public function getChildren()
    {
        return $this->hasMany(Categories::class, ['parent_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(Products::class, ['category_id' => 'id']);
    }

    public function getSelfLink()
    {
        return \yii\helpers\Url::to(['categories/view', 'id' => $this->id], true);
    }
}
