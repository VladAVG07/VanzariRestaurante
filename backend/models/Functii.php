<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "functii".
 *
 * @property int $id
 * @property string $nume
 * @property string $data_inceput
 * @property string|null $data_sfarsit
 *
 * @property FunctiiPersoane[] $functiiPersoanes
 */
class Functii extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'functii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nume', 'data_inceput'], 'required'],
            [['data_inceput', 'data_sfarsit'], 'safe'],
            [['nume'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nume' => 'Nume',
            'data_inceput' => 'Data Inceput',
            'data_sfarsit' => 'Data Sfarsit',
        ];
    }
    
    
    public function salveazaFunctie(){
        $transaction = Yii::$app->db->beginTransaction();
        $save=$this->save();
        $restaurantFunctie = new RestauranteFunctii();
        $restaurantFunctie->restaurant = RestauranteUser::findOne(['user' => Yii::$app->user->id])->restaurant;
        $restaurantFunctie->functie = $this->id;
        $restaurantFunctie->data_ora = Date('Y-m-d H:i:s');
        $save = $restaurantFunctie->save();
        if ($save){
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }
    
    /**
     * Gets query for [[FunctiiPersoanes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFunctiiPersoanes()
    {
        return $this->hasMany(FunctiiPersoane::class, ['functie' => 'id']);
    }
}
