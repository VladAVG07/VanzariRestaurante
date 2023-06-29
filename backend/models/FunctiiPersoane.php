<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "functii_persoane".
 *
 * @property int $id
 * @property int $persoana
 * @property int $functie
 * @property string $data_inceput
 * @property string|null $data_sfarsit
 * @property int $valid
 *
 * @property Functii $functie0
 * @property Persoane $persoana0
 */
class FunctiiPersoane extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'functii_persoane';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['persoana', 'functie', 'data_inceput', 'valid'], 'required'],
            [['persoana', 'functie', 'valid'], 'integer'],
            [['data_inceput', 'data_sfarsit'], 'safe'],
            [['persoana'], 'exist', 'skipOnError' => true, 'targetClass' => Persoane::class, 'targetAttribute' => ['persoana' => 'id']],
            [['functie'], 'exist', 'skipOnError' => true, 'targetClass' => Functii::class, 'targetAttribute' => ['functie' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'persoana' => 'Persoana',
            'functie' => 'Functie',
            'data_inceput' => 'Data Inceput',
            'data_sfarsit' => 'Data Sfarsit',
        ];
    }

    /**
     * Gets query for [[Functie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFunctie0()
    {
        return $this->hasOne(Functii::class, ['id' => 'functie']);
    }

    /**
     * Gets query for [[Persoana0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPersoana0()
    {
        return $this->hasOne(Persoane::class, ['id' => 'persoana']);
    }
}
