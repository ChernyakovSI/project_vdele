<?php

use yii\db\Migration;

/**
 * Class m180715_090334_update_user_table_create
 */
class m180715_090334_update_user_table_create extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user', 'created_at');
        $this->addColumn('user', 'created_at', $this->integer()->notNull()->defaultValue(time()));

        $this->dropColumn('user', 'updated_at');
        $this->addColumn('user', 'updated_at', $this->integer()->notNull()->defaultValue(time()));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180715_090334_update_user_table_create cannot be reverted.\n";

        $this->dropColumn('user', 'created_at');
        $this->addColumn('user', 'created_at', $this->integer()->notNull());

        $this->dropColumn('user', 'updated_at');
        $this->addColumn('user', 'updated_at', $this->integer()->notNull());

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180715_090334_update_user_table_create cannot be reverted.\n";

        return false;
    }
    */
}
