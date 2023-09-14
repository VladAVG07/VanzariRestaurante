<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurante_functii".
 *
 * @property int $id
 * @property int $restaurant
 * @property int $functie
 * @property string $data_ora
 *
 * @property Functii $functie0
 * @property Restaurante $restaurant0
 */
class RestauranteFunctii extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurante_functii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant', 'functie'], 'required'],
            [['restaurant', 'functie'], 'integer'],
            [['data_ora'], 'safe'],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurante::class, 'targetAttribute' => ['restaurant' => 'id']],
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
            'restaurant' => 'Restaurant',
            'functie' => 'Functie',
            'data_ora' => 'Data Ora',
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
     * Gets query for [[Restaurant0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant0()
    {
        return $this->hasOne(Restaurante::class, ['id' => 'restaurant']);
    }
}
