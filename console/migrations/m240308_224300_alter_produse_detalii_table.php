<?php

use yii\db\Migration;

/**
 * Class m210307_120001_alter_produse_detalii_table
 */
class m240308_224300_alter_produse_detalii_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('produse_detalii', 'data_inceput', $this->date()->notNull());
        $this->addColumn('produse_detalii', 'data_sfarsit', $this->date()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('produse_detalii', 'data_inceput');
        $this->dropColumn('produse_detalii', 'data_sfarsit');
    }
}
