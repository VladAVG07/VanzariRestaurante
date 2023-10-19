<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "setari_livrare".
 *
 * @property int $id
 * @property int $restaurant
 * @property int $produs
 * @property float $comanda_minima
 *
 * @property Produse $produs0
 * @property Restaurante $restaurant0
 */
class SetariLivrare extends \yii\db\ActiveRecord
{
    
    public $pret;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'setari_livrare';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant', 'produs', 'comanda_minima', 'pret'], 'required'],
            [['restaurant', 'produs'], 'integer'],
            [['comanda_minima','pret'], 'number'],
            [['restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurante::class, 'targetAttribute' => ['restaurant' => 'id']],
            [['produs'], 'exist', 'skipOnError' => true, 'targetClass' => Produse::class, 'targetAttribute' => ['produs' => 'id']],
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
            'produs' => 'Produs',
            'comanda_minima' => 'Comanda minima',
            'pret'=>'Cost livrare',
        ];
    }

    public function salveazaSetareLivrare(){
        $restauranteUser = RestauranteUser::findOne(['user' => \Yii::$app->user->id]);
        $idRestaurant = $restauranteUser->restaurant;
        $pret_curent = $this->pret;
        $comanda_minima = $this->comanda_minima;
        $result = Yii::$app->db->createCommand('SELECT AdaugaCategorieSiProdus(:restaurant_id, :pret_curent, :comanda_minima) as result')
            ->bindValue(':restaurant_id', $idRestaurant)
            ->bindValue(':pret_curent', $pret_curent)
            ->bindValue(':comanda_minima', $comanda_minima)
            ->queryOne();
        if ($result==1)
            return true;
        else
            return false;
    }
    
    /**
     * Gets query for [[Produs0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProdus0()
    {
        return $this->hasOne(Produse::class, ['id' => 'produs']);
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
