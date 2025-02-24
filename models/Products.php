<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string|null $description
 * @property int|null $category_id
 * @property int|null $manufacturer_id
 * @property string|null $SKU
 * @property int|null $stock_quantity
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property float|null $discount_percentage  // Поле для скидки в процентах
 *
 * @property Categories $category
 * @property Manufacturers $manufacturer
 * @property OrderItems[] $orderItems
 * @property ProductCharacteristics[] $productCharacteristics
 * @property ProductImages[] $productImages
 * @property Reviews[] $reviews
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price'], 'required'],
            [['price'], 'number'],
            [['discount_percentage'], 'number'],  // Убедимся, что скидка тоже является числом
            [['description'], 'string'],
            [['category_id', 'manufacturer_id', 'stock_quantity'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['SKU'], 'string', 'max' => 100],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
            [['manufacturer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manufacturers::class, 'targetAttribute' => ['manufacturer_id' => 'id']],
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
            'price' => 'Price',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'manufacturer_id' => 'Manufacturer ID',
            'SKU' => 'Sku',
            'stock_quantity' => 'Stock Quantity',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'discount_percentage' => 'Discount Percentage',  // Новое поле для скидки
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[Manufacturer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturers::class, ['id' => 'manufacturer_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductCharacteristics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCharacteristics()
    {
        return $this->hasMany(ProductCharacteristics::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImages::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['product_id' => 'id']);
    }
}
