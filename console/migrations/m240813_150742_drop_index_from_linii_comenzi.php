<?php

use yii\db\Migration;

/**
 * Class m240813_150742_drop_index_from_linii_comenzi
 */
class m240813_150742_drop_index_from_linii_comenzi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        // Drop the foreign key constraint
        $this->dropForeignKey(
            'comenzi_linii_ibfk_1',      // The name of the foreign key constraint
            'comenzi_linii'    // The table that contains the foreign key
        );

        // Drop the index associated with the foreign key
        $this->dropIndex(
            'comanda',        // The name of the index
            'comenzi_linii'   // The table that contains the index
        );
        $this->addForeignKey(
            'comenzi_linii_ibfk_1', // Name of the foreign key
            'comenzi_linii',            // Name of the table that will hold the foreign key
            'comanda',                  // Name of the column that will hold the foreign key
            'comenzi',         // Name of the table that the foreign key references
            'id',        // Name of the column in the referenced table
            'CASCADE',                  // ON DELETE behavior (e.g., CASCADE, RESTRICT, etc.)
            'CASCADE'                   // ON UPDATE behavior (e.g., CASCADE, RESTRICT, etc.)
        );
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240813_150742_drop_index_from_linii_comenzi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240813_150742_drop_index_from_linii_comenzi cannot be reverted.\n";

        return false;
    }
    */
}
