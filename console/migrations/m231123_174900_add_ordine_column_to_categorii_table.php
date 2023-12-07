<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%categorii}}`.
 */
class m231123_174900_add_ordine_column_to_categorii_table extends Migration {

    public function up() {
        $this->addColumn('{{%categorii}}', 'ordine', $this->integer()->defaultValue(0)->after('parinte'));
    }

    public function down() {
        $this->dropColumn('{{%categorii}}', 'ordine');
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
