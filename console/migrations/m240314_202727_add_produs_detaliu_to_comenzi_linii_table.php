<?php

use yii\db\Migration;

/**
 * Class m240314_202727_add_produs_detaliu_to_comenzi_linii_table
 */
class m240314_202727_add_produs_detaliu_to_comenzi_linii_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->addColumn('{{%comenzi_linii}}', 'produs_detaliu', $this->integer()->null()->defaultValue(null));
        $this->createIndex('idx-comenzi_linii-produs_detaliu', '{{%comenzi_linii}}', 'produs_detaliu');
        $this->addForeignKey('fk-comenzi_linii-produs_detaliu', '{{%comenzi_linii}}', 'produs_detaliu', '{{%produse_detalii}}', 'id', 'SET NULL', 'CASCADE');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240314_202727_add_produs_detaliu_to_comenzi_linii_table cannot be reverted.\n";

        return false;
    }

    public function down()
    {
        $this->dropForeignKey('fk-comenzi_linii-produs_detaliu', '{{%comenzi_linii}}');
        $this->dropIndex('idx-comenzi_linii-produs_detaliu', '{{%comenzi_linii}}');
        $this->dropColumn('{{%comenzi_linii}}', 'produs_detaliu');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240314_202727_add_produs_detaliu_to_comenzi_linii_table cannot be reverted.\n";

        return false;
    }
    */
}
