<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\validators\Validator;

/**
 * This is the model class for table "product_images".
 *
 * @property int $id
 * @property int $product_id
 * @property string $url
 * @property int|null $is_main
 * @property int|null $sort_order
 *
 * @property Products $product
 */
class ProductImages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_images';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'url'], 'required'],
            [['product_id'], 'integer', 'min' => 1], // Проверка, что product_id >= 1
            [['product_id'], 'exist', 'skipOnError' => false, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['url'], 'string', 'max' => 255],

            [['is_main', 'sort_order'], 'integer'],
            [['is_main'], 'default', 'value' => 0], // Значение по умолчанию для is_main
            [['sort_order'], 'default', 'value' => 0], // Значение по умолчанию для sort_order
            [['product_id', 'url', 'is_main', 'sort_order'], 'safe'], // Разрешаем массовое присвоение
        
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'url' => 'Url',
            'is_main' => 'Is Main',
            'sort_order' => 'Sort Order',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

    /**
     * Custom validator to check if the file is an image
     */
    public function validateImage($attribute, $params, $validator)
    {
        $filePath = Yii::getAlias('@webroot') . $this->$attribute;
        if (file_exists($filePath)) {
            $mimeType = FileHelper::getMimeType($filePath);
            $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mimeType, $allowedMimeTypes)) {
                $this->addError($attribute, 'The file must be an image.');
            }
        } else {
            $this->addError($attribute, 'The file does not exist.');
        }
    }
    public function getIsMain()
{
    return (int) $this->is_main;
}

public function getSortOrder()
{
    return (int) $this->sort_order;
}
}