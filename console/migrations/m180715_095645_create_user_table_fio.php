<?php

use yii\db\Migration;

/**
 * Class m180715_095645_create_user_table_fio
 */
class m180715_095645_create_user_table_fio extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'name', $this->string()->notNull());
        $this->addColumn('user', 'surname', $this->string()->notNull());
        $this->addColumn('user', 'middlename', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180715_095645_create_user_table_fio cannot be reverted.\n";

        $this->dropColumn('user', 'name');
        $this->dropColumn('user', 'surname');
        $this->dropColumn('user', 'middlename');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180715_095645_create_user_table_fio cannot be reverted.\n";

        return false;
    }
    */
}
