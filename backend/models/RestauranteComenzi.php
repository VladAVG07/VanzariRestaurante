<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurante_comenzi".
 *
 * @property int $id
 * @property int $restaurant
 * @property int $comanda
 * @property int $numar_comanda
 *
 * @property Comenzi $comanda0
 * @property Restaurante $restaurant0
 */
class RestauranteComenzi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurante_comenzi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant', 'comanda', 'numar_comanda'], 'required'],
            [['restaurant', 'comanda', 'numar_comanda'], 'integer'],
            [['comanda'], 'exist', 'skipOnError' => true, 'targetClass' => Comenzi::class, 'targetAttribute' => ['comanda' => 'id']],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurante::class, 'targetAttribute' => ['restaurant' => 'id']],
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
            'comanda' => 'Comanda',
            'numar_comanda' => 'Numar Comanda',
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
