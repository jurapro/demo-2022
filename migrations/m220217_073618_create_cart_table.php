<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart}}`.
 */
class m220217_073618_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(1),
        ]);

        $this->createIndex(
            'idx-cart-user_id',
            'cart',
            'user_id'
        );

        $this->addForeignKey(
            'fk-cart-user_id',
            'cart',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-cart-product_id',
            'cart',
            'product_id'
        );

        $this->addForeignKey(
            'fk-cart-product_id',
            'cart',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cart}}');
    }
}
