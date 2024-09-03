<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sesiuni_produse".
 *
 * @property int $id
 * @property int $sesiune
 * @property int $produs
 * @property int $cantitate
 *
 * @property Produse $produs0
 * @property Sesiuni $sesiune0
 */
class SesiuniProduse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sesiuni_produse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sesiune', 'produs', 'cantitate'], 'required'],
            [['sesiune', 'produs', 'cantitate'], 'integer'],
            [['sesiune'], 'exist', 'skipOnError' => true, 'targetClass' => Sesiuni::class, 'targetAttribute' => ['sesiune' => 'id']],
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
            'sesiune' => 'Sesiune',
            'produs' => 'Produs',
            'cantitate' => 'Cantitate',
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

    /**
     * Gets query for [[Sesiune0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSesiune0()
    {
        return $this->hasOne(Sesiuni::class, ['id' => 'sesiune']);
    }
}
