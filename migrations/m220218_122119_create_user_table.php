<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m220218_122119_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->notNull(),
            'surname'=>$this->string()->notNull(),
            'patronymic'=>$this->string()->null(),
            'username'=>$this->string()->notNull()->unique(),
            'email'=>$this->string()->notNull()->unique(),
            'password'=>$this->string()->notNull(),
            'role'=>$this->integer()->notNull()->defaultValue(0)
        ]);

        $this->insert('{{%user}}', [
            'name'=>'admin',
            'surname'=>'admin',
            'username'=>'admin',
            'email'=>'admin@admin.ru',
            'password'=>md5('admin11'),
            'role'=>1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
