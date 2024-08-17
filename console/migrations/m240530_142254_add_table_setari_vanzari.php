<?php

use yii\db\Migration;

/**
 * Class m240530_142254_add_table_setari_vanzari
 */
class m240530_142254_add_table_setari_vanzari extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        // Create the setari_vanzari table
        $this->createTable('{{%setari_vanzari}}', [
            'id' => $this->primaryKey(),
            'restaurant' => $this->integer()->notNull(),
            'vanzari_oprite' => $this->boolean()->notNull(),
            'mesaj_oprit' => $this->string(500),
            'mesaj_generic' => $this->string(500),
        ]);

        // Create an index on the restaurant column
        $this->createIndex(
            'idx-setari_vanzari-restaurant',
            '{{%setari_vanzari}}',
            'restaurant'
        );

        // Add foreign key for table `restaurante`
        $this->addForeignKey(
            'fk-setari_vanzari-restaurant',
            '{{%setari_vanzari}}',
            'restaurant',
            '{{%restaurante}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240530_142254_add_table_setari_vanzari cannot be reverted.\n";

        return false;
    }

    public function down()
    {
        // Drop foreign key
        $this->dropForeignKey(
            'fk-setari_vanzari-restaurant',
            '{{%setari_vanzari}}'
        );

        // Drop index
        $this->dropIndex(
            'idx-setari_vanzari-restaurant',
            '{{%setari_vanzari}}'
        );

        // Drop the setari_vanzari table
        $this->dropTable('{{%setari_vanzari}}');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240530_142254_add_table_setari_vanzari cannot be reverted.\n";

        return false;
    }
    */
}
