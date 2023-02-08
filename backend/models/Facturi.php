<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "facturi".
 *
 * @property int $id
 * @property string $serie
 * @property int $comanda
 * @property string $data_emitere
 *
 * @property Comenzi $comanda0
 */
class Facturi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facturi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serie', 'comanda', 'data_emitere'], 'required'],
            [['id', 'comanda'], 'integer'],
            [['data_emitere'], 'safe'],
            [['serie'], 'string', 'max' => 255],
            [['serie'], 'unique'],
            [['comanda'], 'unique'],
            [['comanda'], 'exist', 'skipOnError' => true, 'targetClass' => Comenzi::class, 'targetAttribute' => ['comanda' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serie' => 'Serie',
            'comanda' => 'Comanda',
            'data_emitere' => 'Data Emitere',
        ];
    }

    /**
     * Gets query for [[Comanda0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComanda0()
    {
        return $this->hasOne(Comenzi::class, ['id' => 'comanda']);
    }
}
