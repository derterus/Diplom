<?php

use yii\db\Migration;

/**
 * Class m220301_123456_create_characteristic_values_table
 */
class m250301_141551_create_characteristic_values_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создаем таблицу characteristic_values
        $this->createTable('characteristic_values', [
            'id' => $this->primaryKey(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->string(255)->notNull(),
        ]);

        // Добавляем внешний ключ для characteristic_id
        $this->addForeignKey(
            'fk-characteristic_values-characteristic_id',
            'characteristic_values',
            'characteristic_id',
            'characteristics',
            'id',
            'CASCADE'  // Если характеристика удаляется, все ее значения также удаляются
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Удаляем внешний ключ
        $this->dropForeignKey('fk-characteristic_values-characteristic_id', 'characteristic_values');
        
        // Удаляем таблицу
        $this->dropTable('characteristic_values');
    }
}
