<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "setari_vanzari".
 *
 * @property int $id
 * @property int $restaurant
 * @property int $vanzari_oprite
 * @property string|null $mesaj_oprit
 * @property string|null $mesaj_generic
 *
 * @property Restaurante $restaurant0
 */
class SetariVanzari extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setari_vanzari';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant', 'vanzari_oprite'], 'required'],
            [['restaurant', 'vanzari_oprite'], 'integer'],
            [['mesaj_oprit', 'mesaj_generic'], 'string', 'max' => 500],
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
            'vanzari_oprite' => 'Vanzari Oprite',
            'mesaj_oprit' => 'Mesaj Oprit',
            'mesaj_generic' => 'Mesaj Generic',
        ];
    }

    public function saveOrUpdate(){
        if ($this->isNewRecord){
            $restaurantUser = RestauranteUser::findOne(['user'=>Yii::$app->user->id]);
            $this->restaurant = $restaurantUser->restaurant;
        }
       return $this->save();
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
