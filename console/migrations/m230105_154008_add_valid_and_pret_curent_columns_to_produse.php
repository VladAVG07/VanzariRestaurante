<?php

use yii\db\Migration;

/**
 * Class m230105_154008_add_valid_and_pret_curent_columns_to_produse
 */
class m230105_154008_add_valid_and_pret_curent_columns_to_produse extends Migration {

    /**
     * {@inheritdoc}
     */
//    public function safeUp()
//    {
//
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function safeDown()
//    {
//        echo "m230105_154008_add_valid_and_pret_curent_columns_to_produse cannot be reverted.\n";
//
//        return false;
//    }
    // Use up()/down() to run migration code without a transaction.
    public function up() {
        $this->addColumn('{{%produse}}', 'pret_curent', $this->decimal(9, 2));
        $this->addColumn('{{%produse}}', 'valid', $this->boolean()->defaultValue(true));
    }

    public function down() {
        $this->dropColumn('{{%produse}}', 'valid');
        $this->dropColumn('{{%produse}}', 'pret_curent');
                
    }

}
