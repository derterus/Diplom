<?php

use yii\db\Migration;

/**
 * Class m250212_145311_init
 */
class m250212_145604_products_discont extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('products', 'discount_percentage', $this->float()->defaultValue(0));  // Скидка в процентах, по умолчанию 0

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250212_145311_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250212_145311_init cannot be reverted.\n";

        return false;
    }
    */
}
