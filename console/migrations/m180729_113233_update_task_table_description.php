<?php

use yii\db\Migration;

/**
 * Class m180729_113233_update_task_table_description
 */
class m180729_113233_update_task_table_description extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'description', $this->text()->notNull()->defaultValue(''));
        $this->addColumn('task', 'result', $this->text()->notNull()->defaultValue(''));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'description');
        $this->dropColumn('task', 'result');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180729_113233_update_task_table_description cannot be reverted.\n";

        return false;
    }
    */
}
