<?php

use yii\db\Migration;

/**
 * Class m240627_150705_add_column_stripe_to_restaurante
 */
class m240627_150705_add_column_stripe_to_restaurante extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    public function up()
    {
        $this->addColumn('{{%restaurante}}', 'stripe_api_key', $this->string()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%restaurante}}', 'stripe_api_key');
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240627_150705_add_column_stripe_to_restaurante cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240627_150705_add_column_stripe_to_restaurante cannot be reverted.\n";

        return false;
    }
    */
}
