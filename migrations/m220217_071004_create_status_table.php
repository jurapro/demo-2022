<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m220217_071004_create_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->unique()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->insert('{{%status}}', [
            'name' => 'Новый',
            'code' => 'new',
        ]);

        $this->insert('{{%status}}', [
            'name' => 'Подтвержденный',
            'code' => 'confirm',
        ]);

        $this->insert('{{%status}}', [
            'name' => 'Отклоненный',
            'code' => 'rejected',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status}}');
    }
}
