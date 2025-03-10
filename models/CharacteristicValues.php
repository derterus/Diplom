<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "characteristic_values".
 *
 * @property int $id
 * @property int $characteristic_id
 * @property string $value
 */
class CharacteristicValues extends ActiveRecord
{
    public static function tableName()
    {
        return 'characteristic_values';
    }

    public function rules()
    {
        return [
            [['characteristic_id', 'value'], 'required'],
            [['characteristic_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'characteristic_id' => 'Characteristic ID',
            'value' => 'Value',
        ];
    }

    // Добавляем связь с характеристиками
    public function getCharacteristic()
    {
        return $this->hasOne(Characteristics::class, ['id' => 'characteristic_id']);
    }
}
