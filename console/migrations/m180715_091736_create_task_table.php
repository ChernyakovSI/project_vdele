<?php

use yii\db\Migration;

/**
 * Handles the creation of table `task`.
 */
class m180715_091736_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('task', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'id_doer' => $this->integer(),
            'id_manager' => $this->integer(),
            'deadline' => $this->integer(),
            'finish_date' => $this->integer(),
            'id_status' => $this->integer(),
            'id_project' => $this->integer(),
            'created_at' => $this->integer()->notNull()->defaultValue(time()),
            'updated_at' => $this->integer()->notNull()->defaultValue(time()),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('task');
    }
}
