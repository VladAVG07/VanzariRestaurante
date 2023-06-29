<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "comenzi_linii".
 *
 * @property int $id
 * @property int $comanda
 * @property int $produs
 * @property string $numeProdus
 * @property int $cantitate
 * @property float $pret
 *
 * @property Comenzi $comanda0
 * @property Produse $produs0
 */
class ComenziLinii extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'comenzi_linii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['comanda', 'produs', 'cantitate', 'pret'], 'required'],
            [['comanda', 'produs', 'cantitate'], 'integer'],
            [['pret'], 'number'],
            [['comanda', 'produs'], 'unique', 'targetAttribute' => ['comanda', 'produs']],
            [['comanda'], 'exist', 'skipOnError' => true, 'targetClass' => Comenzi::class, 'targetAttribute' => ['comanda' => 'id']],
            [['produs'], 'exist', 'skipOnError' => true, 'targetClass' => Produse::class, 'targetAttribute' => ['produs' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'comanda' => 'Comanda',
            'produs' => 'Produs',
            'cantitate' => 'Cantitate',
            'pret' => 'Pret',
        ];
    }

    /**
     * Gets query for [[Comanda0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComanda0() {
        return $this->hasOne(Comenzi::class, ['id' => 'comanda']);
    }

    /**
     * Gets query for [[Produs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdus0() {
        return $this->hasOne(Produse::class, ['id' => 'produs']);
    }
}
