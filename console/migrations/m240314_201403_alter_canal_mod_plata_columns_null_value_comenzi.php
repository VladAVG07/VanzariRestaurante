<?php

use yii\db\Migration;

/**
 * Class m240314_201403_alter_canal_mod_plata_columns_null_value_comenzi
 */
class m240314_201403_alter_canal_mod_plata_columns_null_value_comenzi extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
    }

    public function up() {
        $this->alterColumn('{{%comenzi}}', 'canal', $this->string(20)->null());
        $this->alterColumn('{{%comenzi}}', 'mod_plata', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        echo "m240314_201403_alter_canal_mod_plata_columns_null_value_comenzi cannot be reverted.\n";

        return false;
    }

    public function down() {
        $this->alterColumn('{{%comenzi}}', 'canal', $this->string(20)->notNull());
        $this->alterColumn('{{%comenzi}}', 'mod_plata', $this->integer()->notNull());
    }

    /*
      // Use up()/down() to run migration code without a transaction.
      public function up()
      {

      }

      public function down()
      {
      echo "m240314_201403_alter_canal_mod_plata_columns_null_value_comenzi cannot be reverted.\n";

      return false;
      }
     */
}
