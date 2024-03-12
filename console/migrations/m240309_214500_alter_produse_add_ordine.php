<?php

use yii\db\Migration;

/**
 * Class m210307_120001_alter_produse_detalii_table
 */
class m240309_214500_alter_produse_add_ordine extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('produse', 'ordine', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('produse', 'ordine');
    }
}
