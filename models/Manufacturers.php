<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "manufacturers".
 *
 * @property int $id
 * @property string $name
 * @property string|null $country
 * @property string|null $logo
 * @property string|null $website
 * @property string|null $contact_email
 * @property string|null $phone
 * @property string|null $description
 *
 * @property Products[] $products
 */
class Manufacturers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'manufacturers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['name', 'logo', 'website', 'contact_email'], 'string', 'max' => 255],
            [['country'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 20],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'country' => 'Country',
            'logo' => 'Logo',
            'website' => 'Website',
            'contact_email' => 'Contact Email',
            'phone' => 'Phone',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Products]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Products::class, ['manufacturer_id' => 'id']);
    }
}
