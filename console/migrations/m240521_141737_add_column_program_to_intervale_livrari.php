<?php

use yii\db\Migration;

/**
 * Class m240521_141737_add_column_program_to_intervale_livrari
 */
class m240521_141737_add_column_program_to_intervale_livrari extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->addColumn('{{%intervale_livrare}}', 'program', $this->integer());
    }
    
    /**
     * {@inheritdoc}
     */
    
    public function down()
    {
        $this->dropColumn('{{%intervale_livrare}}', 'program');
    }
    public function safeDown()
    {
        echo "m240521_141737_add_column_program_to_intervale_livrari cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240521_141737_add_column_program_to_intervale_livrari cannot be reverted.\n";

        return false;
    }
    */
}
