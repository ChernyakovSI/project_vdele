<?php

use yii\db\Migration;

/**
 * Class m190519_162303_update_user_table_info
 */
class m190519_162303_update_user_table_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'gender', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('user', 'date_of_birth', $this->integer()->notNull()->defaultValue(time()));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'gender');
        $this->dropColumn('user', 'date_of_birth');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190519_162303_update_user_table_info cannot be reverted.\n";

        return false;
    }
    */
}
