<?php

use yii\db\Migration;

/**
 * Class m240430_125055_add_table_restaurante_comenzi
 */
class m240430_125055_add_table_restaurante_comenzi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up(){
        $this->createTable('{{%restaurante_comenzi}}', [
            'id' => $this->primaryKey()->unsigned(),
            'restaurant' => $this->integer()->notNull(),
            'comanda' => $this->integer()->notNull(),
            'numar_comanda' => $this->integer()->notNull(),
        ]);

        // Add foreign key for restaurant_id
        $this->addForeignKey(
            'fk-restaurante_comenzi-restaurant',
            '{{%restaurante_comenzi}}',
            'restaurant',
            '{{%restaurante}}', // Assuming your restaurant table is named 'restaurante'
            'id',
            'CASCADE'
        );

        // Add foreign key for comanda_id
        $this->addForeignKey(
            'fk-restaurante_comenzi-comanda',
            '{{%restaurante_comenzi}}',
            'comanda',
            '{{%comenzi}}', // Assuming your comenzi table is named 'comenzi'
            'id',
            'CASCADE'
        );
    }
    
    public function down(){
        // Drop foreign key for comanda_id
        $this->dropForeignKey('fk-restaurante_comenzi-comanda', '{{%restaurante_comenzi}}');

        // Drop foreign key for restaurant_id
        $this->dropForeignKey('fk-restaurante_comenzi-restaurant', '{{%restaurante_comenzi}}');

        // Drop the table
        $this->dropTable('{{%restaurante_comenzi}}');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240430_125055_add_table_restaurante_comenzi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240430_125055_add_table_restaurante_comenzi cannot be reverted.\n";

        return false;
    }
    */
}
