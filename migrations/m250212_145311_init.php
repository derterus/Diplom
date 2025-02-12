<?php

use yii\db\Migration;

/**
 * Class m250212_145311_init
 */
class m250212_145311_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

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
