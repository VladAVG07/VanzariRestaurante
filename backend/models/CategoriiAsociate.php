<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "categorii_asociate".
 *
 * @property int $id
 * @property int $categorie
 * @property int $categorie_asociata
 * @property int $disponibil
 *
 * @property Categorii $categorie0
 * @property Categorii $categorieAsociata
 */
class CategoriiAsociate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorii_asociate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categorie', 'categorie_asociata'], 'required'],
            [['categorie', 'categorie_asociata', 'disponibil'], 'integer'],
            [['categorie'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['categorie' => 'id']],
            [['categorie_asociata'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['categorie_asociata' => 'id']],
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
            'categorie_asociata' => 'Categorie Asociata',
            'disponibil' => 'Disponibil',
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

    /**
     * Gets query for [[CategorieAsociata]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorieAsociata()
    {
        return $this->hasOne(Categorii::class, ['id' => 'categorie_asociata']);
    }
}
