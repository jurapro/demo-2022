<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m220218_123049_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull(),
            'timestamp'=>$this->timestamp(),
            'category_id'=>$this->integer()->defaultValue(1),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-product-category_id',
            '{{%product}}',
            'category_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-product-category_id',
            '{{%product}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
