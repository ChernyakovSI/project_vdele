<?php

use yii\db\Migration;

/**
 * Class m180714_180316_update_user_table
 */
class m180714_180316_update_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'id_role', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180714_180316_update_user_table cannot be reverted.\n";

        $this->dropColumn('user', 'id_role');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180714_180316_update_user_table cannot be reverted.\n";

        return false;
    }
    */
}
