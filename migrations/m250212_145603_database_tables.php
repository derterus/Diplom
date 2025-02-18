<?php

use yii\db\Migration;

/**
 * Class m250212_145603_database_tables
 */
class m250212_145603_database_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('categories', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'description' => $this->text(),
            'meta_keywords' => $this->text(),
            'meta_description' => $this->text(),
            'image' => $this->string(255),
            'parent_id' => $this->integer()->null(),
            'created_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('NOW()'))->append('ON UPDATE NOW()'),
            'FOREIGN KEY (parent_id)' => 'REFERENCES categories(id)'
        ]);

        $this->createTable('manufacturers', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull()->unique(),
            'country' => $this->string(100),
            'logo' => $this->string(255),
            'website' => $this->string(255),
            'contact_email' => $this->string(255),
            'phone' => $this->string(20),
            'description' => $this->text()
        ]);

        $this->createTable('characteristics', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()
        ]);

        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'description' => $this->text(),
            'category_id' => $this->integer(),
            'manufacturer_id' => $this->integer(),
            'SKU' => $this->string(100),
            'stock_quantity' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('NOW()'))->append('ON UPDATE NOW()'),
            'FOREIGN KEY (category_id)' => 'REFERENCES categories(id)',
            'FOREIGN KEY (manufacturer_id)' => 'REFERENCES manufacturers(id)'
        ]);

        $this->createTable('product_images', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'url' => $this->string(255)->notNull(),
            'is_main' => $this->boolean()->defaultValue(false),
            'sort_order' => $this->integer()->defaultValue(0),
            'FOREIGN KEY (product_id)' => 'REFERENCES products(id) ON DELETE CASCADE'
        ]);

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->text()->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'status' => $this->smallInteger()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'access_token' => $this->string(255),
            'role' => $this->text()->notNull()
        ]);
        $this->createTable('reviews', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'rating' => $this->integer()->notNull(),
            'title' => $this->string(255),
            'comment' => $this->text(),
            'created_at' => $this->timestamp()->defaultValue(new \yii\db\Expression('NOW()')),
            'is_approved' => $this->tinyInteger()->defaultValue(0),
            'helpful_votes' => $this->integer()->defaultValue(0),
            'FOREIGN KEY (user_id)' => 'REFERENCES user(id)',
            'FOREIGN KEY (product_id)' => 'REFERENCES products(id) ON DELETE CASCADE'
        ]);

        $this->createTable('product_characteristics', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'characteristic_id' => $this->integer()->notNull(),
            'value' => $this->string(255),
            'FOREIGN KEY (product_id)' => 'REFERENCES products(id) ON DELETE CASCADE',
            'FOREIGN KEY (characteristic_id)' => 'REFERENCES characteristics(id)'
        ]);

       

        $this->createTable('orders', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()'))->append('ON UPDATE NOW()'),
            'total_amount' => $this->decimal(10, 2)->notNull(),
            'status' => $this->string(50),
            'shipping_address' => $this->text(),
            'payment_method' => $this->string(50),
            'shipping_cost' => $this->decimal(10, 2),
            'tracking_number' => $this->string(255),
            'FOREIGN KEY (user_id)' => 'REFERENCES user(id)'
        ]);

        $this->createTable('order_items', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'discount' => $this->decimal(10, 2),
            'item_total' => $this->decimal(10, 2),
            'created_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()')),
            'updated_at' => $this->dateTime()->defaultValue(new \yii\db\Expression('NOW()'))->append('ON UPDATE NOW()'),
            'FOREIGN KEY (order_id)' => 'REFERENCES orders(id)',
            'FOREIGN KEY (product_id)' => 'REFERENCES products(id)'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('order_items');
        $this->dropTable('orders');
        $this->dropTable('user');
        $this->dropTable('product_characteristics');
        $this->dropTable('reviews');
        $this->dropTable('product_images');
        $this->dropTable('products');
        $this->dropTable('characteristics');
        $this->dropTable('manufacturers');
        $this->dropTable('categories');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250212_145603_database_tables cannot be reverted.\n";

        return false;
    }
    */
}