<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurante_user".
 *
 * @property int $id
 * @property int $restaurant
 * @property int $user
 * @property int $valid
 *
 * @property Restaurante $restaurant0
 * @property User $user0
 */
class RestauranteUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurante_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant', 'user', 'valid'], 'required'],
            [['restaurant', 'user', 'valid'], 'integer'],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurante::class, 'targetAttribute' => ['restaurant' => 'id']],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::class, 'targetAttribute' => ['user' => 'id']],
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
            'user' => 'User',
            'valid' => 'Valid',
        ];
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

    /**
     * Gets query for [[User0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::class, ['id' => 'user']);
    }
}
