<?php

use yii\db\Migration;

/**
 * Handles the creation of table `produse_detalii`.
 */
class m240222_220012_create_produse_detalii_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('produse_detalii', [
            'id' => $this->primaryKey(),
            'produs' => $this->integer()->notNull(),
            'descriere' => $this->string(150),
            'pret' => $this->decimal(20, 2),
            'disponibil' => $this->boolean()->defaultValue(true),
        ],$tableOptions);

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-produse_detalii-produs_id',
            'produse_detalii',
            'produs',
            'produse', // Assuming the table name for products is 'produs'
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key constraint
        $this->dropForeignKey('fk-produse_detalii-produs_id', 'produse_detalii');

        // Drop the table
        $this->dropTable('produse_detalii');
    }
}
