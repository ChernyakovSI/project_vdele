<?php

use yii\db\Migration;

/**
 * Class m180729_101409_insert_role_table
 */
class m180729_101409_insert_role_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('role', ['id' => 1, 'name' => 'Гость', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
        $this->insert('role', ['id' => 2, 'name' => 'Пользователь', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
        $this->insert('role', ['id' => 3, 'name' => 'Администратор', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('role', '`id` = 1');
        $this->delete('role', '`id` = 2');
        $this->delete('role', '`id` = 3');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180729_101409_insert_role_table cannot be reverted.\n";

        return false;
    }
    */
}
