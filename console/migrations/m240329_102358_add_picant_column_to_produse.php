<?php

use yii\db\Migration;

/**
 * Class m240329_102358_add_picant_column_to_produse
 */
class m240329_102358_add_picant_column_to_produse extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->addColumn('{{%produse}}', 'picant', $this->boolean()->notNull()->defaultValue(false));
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240329_102358_add_picant_column_to_produse cannot be reverted.\n";

        return false;
    }
    
    public function down()
    {
        $this->dropColumn('{{%produse}}', 'picant');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240329_102358_add_picant_column_to_produse cannot be reverted.\n";

        return false;
    }
    */
}
