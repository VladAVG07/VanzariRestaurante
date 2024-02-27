<?php

use yii\db\Migration;

/**
 * Class m************_add_fk_to_restaurants
 */
class m240213_225100_alter_table_restaurante_add_tip_restaurant_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurante', 'tip_restaurant', $this->integer()->after('id')->comment('Tipul din care face parte restaurantul'));

        // Add foreign key constraint
        $this->addForeignKey(
            'fk-restaurants-tip_restaurant',
            'restaurante',
            'tip_restaurant',
            'tipuri_de_restaurante',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the foreign key first
        $this->dropForeignKey('fk-restaurants-tip_restaurant', 'restaurante');

        // Then drop the column
        $this->dropColumn('restaurante', 'tip_restaurant');
    }
}
