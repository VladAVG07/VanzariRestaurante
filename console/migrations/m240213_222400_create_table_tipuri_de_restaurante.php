<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tipuri_de_restaurante}}`.
 */
class m240213_222400_create_table_tipuri_de_restaurante extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%tipuri_de_restaurante}}', [
            'id' => $this->primaryKey(),
            'nume' => $this->string()->notNull()->unique(),
        ],$tableOptions);

        // Inserarea datelor initiale
        $tipuriDeRestaurante = [
            'Americană',
            'Asiatică',
            'Băuturi',
            'Brutărie și patiserie',
            'Burgeri',
            'Carne',
            'Ceai și cafea',
            'Chinezească',
            'Deserturi',
            'Doner',
            'Fast food',
            'Flori',
            'Fructe de mare',
            'Grătar',
            'Grecească',
            'Gustări',
            'Internațională',
            'Italiană',
            'Înghețată',
            'Kebab',
            'Mexicană',
            'Mic dejun',
            'Orientală',
            'Paste',
            'Pește',
            'Pizza',
            'Porc',
            'Preparate gătite',
            'Salate',
            'Sandwich-uri',
            'Sănătos',
            'Shaorma',
            'Supe/Ciorbe',
            'Sushi',
            'Tacos',
            'Tradițională',
            'Vegetariană',
            'Vită',
        ];

        foreach ($tipuriDeRestaurante as $nume) {
            $this->insert('{{%tipuri_de_restaurante}}', ['nume' => $nume]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tipuri_de_restaurante}}');
    }
}
