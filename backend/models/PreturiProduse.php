<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "preturi_produse".
 *
 * @property int $id
 * @property int $produs
 * @property float $pret
 * @property string $data_inceput
 * @property string|null $data_sfarsit
 * @property int $valid
 *
 * @property Produse $produs0
 */
class PreturiProduse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preturi_produse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['produs', 'pret', 'data_inceput', 'valid'], 'required'],
            [['produs', 'valid'], 'integer'],
            [['pret'], 'number'],
            [['data_inceput', 'data_sfarsit'], 'safe'],
           // [['data_inceput', 'data_sfarsit'], 'unique'],
            [['produs'], 'exist', 'skipOnError' => true, 'targetClass' => Produse::class, 'targetAttribute' => ['produs' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'produs' => 'Produs',
            'pret' => 'Pret',
            'data_inceput' => 'Data Inceput',
            'data_sfarsit' => 'Data Sfarsit',
            'valid' => 'Valid',
        ];
    }

    /**
     * Gets query for [[Produs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdus0()
    {
        return $this->hasOne(Produse::class, ['id' => 'produs']);
    }
}
