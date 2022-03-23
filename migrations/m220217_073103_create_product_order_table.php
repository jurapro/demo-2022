<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product_order}}`.
 */
class m220217_073103_create_product_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product_order}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(1),
            'price' => $this->integer()->notNull()->defaultValue(0),
        ]);

        $this->createIndex(
            'idx-product_order-order_id',
            'product_order',
            'order_id'
        );

        $this->addForeignKey(
            'fk-product_order-order_id',
            'product_order',
            'order_id',
            'order',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-product_order-product_id',
            'product_order',
            'product_id'
        );

        $this->addForeignKey(
            'fk-product_order-product_id',
            'product_order',
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
        $this->dropTable('{{%product_order}}');
    }
}
