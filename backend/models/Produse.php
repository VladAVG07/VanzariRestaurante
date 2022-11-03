<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "produse".
 *
 * @property int $id
 * @property int $categorie
 * @property int $cod_produs
 * @property string $nume
 * @property string $descriere
 * @property string $data_productie
 *
 * @property Categorii $categorie0
 */
class Produse extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categorie', 'cod_produs', 'nume', 'descriere', 'data_productie'], 'required'],
            [['categorie', 'cod_produs'], 'integer'],
            [['data_productie'], 'safe'],
            [['nume'], 'string', 'max' => 100],
            [['descriere'], 'string', 'max' => 200],
            [['cod_produs'], 'unique'],
            [['categorie'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['categorie' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categorie' => 'Categorie',
            'cod_produs' => 'Cod produs',
            'nume' => 'Nume',
            'descriere' => 'Descriere',
            'data_productie' => 'Data productie',
        ];
    }

    /**
     * Gets query for [[Categorie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorie0()
    {
        return $this->hasOne(Categorii::class, ['id' => 'categorie']);
    }
}
