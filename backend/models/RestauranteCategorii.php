<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurante_categorii".
 *
 * @property int $id
 * @property int $restaurant
 * @property int $categorie
 * @property string $data_ora
 *
 * @property Categorii $categorie0
 * @property Restaurante $restaurant0
 */
class RestauranteCategorii extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurante_categorii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant', 'categorie'], 'required'],
            [['restaurant', 'categorie'], 'integer'],
            [['data_ora'], 'safe'],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurante::class, 'targetAttribute' => ['restaurant' => 'id']],
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
            'restaurant' => 'Restaurant',
            'categorie' => 'Categorie',
            'data_ora' => 'Data Ora',
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
     * Gets query for [[Restaurant0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant0()
    {
        return $this->hasOne(Restaurante::class, ['id' => 'restaurant']);
    }
}
