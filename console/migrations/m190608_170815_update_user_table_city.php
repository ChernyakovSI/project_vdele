<?php

use yii\db\Migration;

/**
 * Class m190608_170815_update_user_table_city
 */
class m190608_170815_update_user_table_city extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'id_city', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'id_city');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190608_170815_update_user_table_city cannot be reverted.\n";

        return false;
    }
    */
}
