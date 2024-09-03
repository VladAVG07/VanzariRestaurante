<?php

use yii\db\Migration;

/**
 * Class m240507_144316_add_table_intervale_livrare
 */
class m240507_144316_add_table_intervale_livrare extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->createTable('intervale_livrare', [
            'id' => $this->primaryKey(),
            'restaurant' => $this->integer()->notNull(), // Changed column name
            'ora_inceput' => $this->string(5),
            'ora_sfarsit' => $this->string(5),
            'ziua_saptamanii' => $this->integer(),
        ]);

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-intervale_livrare-restaurant',
            'intervale_livrare',
            'restaurant',
            'restaurante', // Changed to match the referenced table
            'id',
            'CASCADE'
        );
    }
    
    public function down()
    {
        // Drop foreign key constraint
        $this->dropForeignKey('fk-intervale_livrare-restaurant', 'intervale_livrare');

        // Drop the table
        $this->dropTable('intervale_livrare');
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240507_144316_add_table_intervale_livrare cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240507_144316_add_table_intervale_livrare cannot be reverted.\n";

        return false;
    }
    */
}
