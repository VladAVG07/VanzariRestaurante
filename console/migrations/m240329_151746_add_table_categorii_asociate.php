<?php

use yii\db\Migration;

/**
 * Class m240329_151746_add_table_categorii_asociate
 */
class m240329_151746_add_table_categorii_asociate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

     public function up()
    {
        $this->createTable('{{%categorii_asociate}}', [
            'id' => $this->primaryKey()->unsigned(),
            'categorie' => $this->integer()->notNull(),
            'categorie_asociata' => $this->integer()->notNull(),
            'disponibil' => $this->boolean()->notNull()->defaultValue(true),
        ]);

        // Add index keys
        $this->createIndex(
            'idx-categorii_asociate-categorie',
            '{{%categorii_asociate}}',
            'categorie'
        );

        $this->createIndex(
            'idx-categorii_asociate-categorie_asociata',
            '{{%categorii_asociate}}',
            'categorie_asociata'
        );

        // Add foreign key constraints
        $this->addForeignKey(
            'fk-categorii_asociate-categorie',
            '{{%categorii_asociate}}',
            'categorie',
            '{{%categorii}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-categorii_asociate-categorie_asociata',
            '{{%categorii_asociate}}',
            'categorie_asociata',
            '{{%categorii}}',
            'id',
            'CASCADE'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240329_151746_add_table_categorii_asociate cannot be reverted.\n";

        return false;
    }

    public function down()
    {
        // Drop foreign keys
        $this->dropForeignKey('fk-categorii_asociate-categorie', '{{%categorii_asociate}}');
        $this->dropForeignKey('fk-categorii_asociate-categorie_asociata', '{{%categorii_asociate}}');

        // Drop index keys
        $this->dropIndex('idx-categorii_asociate-categorie', '{{%categorii_asociate}}');
        $this->dropIndex('idx-categorii_asociate-categorie_asociata', '{{%categorii_asociate}}');

        // Drop the table
        $this->dropTable('{{%categorii_asociate}}');
    }
    
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240329_151746_add_table_categorii_asociate cannot be reverted.\n";

        return false;
    }
    */
}
