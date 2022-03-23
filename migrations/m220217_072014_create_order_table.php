<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m220217_072014_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'date' => $this->timestamp(),
            'user_id' => $this->integer()->notNull(),
            'status_id' => $this->integer()->notNull()->defaultValue(1),
            'rejection_reason' => $this->text()->null(),
        ]);

        $this->createIndex(
            'idx-order-user_id',
            'order',
            'user_id'
        );

        $this->addForeignKey(
            'fk-order-user_id',
            'order',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-order-status_id',
            'order',
            'status_id'
        );

        $this->addForeignKey(
            'fk-order-status_id',
            'order',
            'status_id',
            'status',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
    }
}
