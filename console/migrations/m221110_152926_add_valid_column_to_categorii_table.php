<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%categorii}}`.
 */
class m221110_152926_add_valid_column_to_categorii_table extends Migration {

    public function up() {
        $this->addColumn('{{%categorii}}', 'valid', $this->boolean()->defaultValue(true));
    }

    public function down() {
        $this->dropColumn('{{%categorii}}', 'valid');
    }

    /**
     * {@inheritdoc}
     */
//    public function safeUp()
//    {
//    }

    /**
     * {@inheritdoc}
     */
//    public function safeDown()
//    {
//    }
}
