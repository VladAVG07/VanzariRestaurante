<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%restaurante}}`.
 */
class m240215_212700_add_poza_prezentare_column_to_restaurante_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%restaurante}}', 'poza_prezentare', $this->string(150)->after('numar_telefon')->comment('Poza de prezentare a restaurantului'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%restaurante}}', 'poza_prezentare');
    }
}
