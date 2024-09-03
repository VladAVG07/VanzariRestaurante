<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%comenzi_detalii}}`.
 */
class m230119_162220_add_detalii_column_to_comenzi_detalii_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%comenzi_detalii}}', 'detalii', $this->text(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%comenzi_detalii}}', 'detalii');
    }
}
