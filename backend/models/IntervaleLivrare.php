<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "intervale_livrare".
 *
 * @property int $id
 * @property int $restaurant
 * @property string|null $ora_inceput
 * @property string|null $ora_sfarsit
 * @property int|null $ziua_saptamanii
 * @property int $program
 *
 * @property Restaurante $restaurant0
 */
class IntervaleLivrare extends \yii\db\ActiveRecord
{
    public $zileSaptamana;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'intervale_livrare';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant'], 'required'],
            [['restaurant', 'ziua_saptamanii'], 'integer'],
            [['ziua_saptamanii','zileSaptamana','program'], 'safe'],
            [['ora_inceput', 'ora_sfarsit'], 'string', 'max' => 5],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurante::class, 'targetAttribute' => ['restaurant' => 'id']],
        ];
    }

    public function salveazaIntervaleLivrare(){
        $transaction = \Yii::$app->db->beginTransaction();
        $zile = count($this->zileSaptamana);
        $restaurant = RestauranteUser::findOne(['user'=> \Yii::$app->user->id])->restaurant;
        $save=true;
        for ($i=0; $i<$zile; $i++){
            $intervalLivrare = new IntervaleLivrare();
            $intervalLivrare->restaurant = $restaurant;
            $intervalLivrare->ora_inceput = $this->ora_inceput;
            $intervalLivrare->ora_sfarsit = $this->ora_sfarsit;
            $intervalLivrare->ziua_saptamanii = $this->zileSaptamana[$i];
            $intervalLivrare->program = $this->program;
            $save = $save && $intervalLivrare->save();
        }
       if ($save){
           $transaction->commit();
           return true;
       }
       $transaction->rollBack();
       return false;
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'restaurant' => 'Restaurant',
            'ora_inceput' => 'Ora Inceput',
            'ora_sfarsit' => 'Ora Sfarsit',
            'ziua_saptamanii' => 'Ziua Saptamanii',
            'program' => 'Program'
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
}
