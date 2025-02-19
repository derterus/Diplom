<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_characteristics".
 *
 * @property int $id
 * @property int $product_id
 * @property int $characteristic_id
 * @property string|null $value
 *
 * @property Characteristics $characteristic
 * @property Products $product
 */
class ProductCharacteristics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_characteristics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'characteristic_id'], 'required'],
            [['product_id', 'characteristic_id'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => false, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
            [['value'], 'string'],

            // Проверка диапазона characteristic_id
            ['characteristic_id', 'exist',
                'targetClass' => Characteristics::class, // Замени на имя твоей модели Characteristics
                'targetAttribute' => 'id',
                'message' => 'Characteristic ID does not exist.'
            ],

            // Проверка уникальности characteristic_id для одного product_id
            [['product_id', 'characteristic_id'], 'unique',
                'targetAttribute' => ['product_id', 'characteristic_id'],
                'message' => 'This characteristic is already assigned to this product.'
            ],
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
            'characteristic_id' => 'Characteristic ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[Characteristic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCharacteristic()
    {
        return $this->hasOne(Characteristics::class, ['id' => 'characteristic_id']);
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
}
