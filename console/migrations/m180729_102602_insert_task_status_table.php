<?php

use yii\db\Migration;

/**
 * Class m180729_102602_insert_task_status_table
 */
class m180729_102602_insert_task_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('task_status', ['id' => 1, 'name' => 'Черновик', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
        $this->insert('task_status', ['id' => 2, 'name' => 'В работе', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
        $this->insert('task_status', ['id' => 3, 'name' => 'Выполнено', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
        $this->insert('task_status', ['id' => 4, 'name' => 'Проверено', 'created_at' => idate('U'), 'updated_at' => idate('U')]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('task_status', '`id` = 1');
        $this->delete('task_status', '`id` = 2');
        $this->delete('task_status', '`id` = 3');
        $this->delete('task_status', '`id` = 4');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180729_102602_insert_task_status_table cannot be reverted.\n";

        return false;
    }
    */
}
