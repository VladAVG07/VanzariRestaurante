<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "categorii".
 *
 * @property int $id
 * @property string $nume
 * @property string $descriere
 * @property int|null $parinte
 * @property boolean $valid
 *
 * @property Categorii[] $categoriis
 * @property Categorii $parinte0
 * @property Produse[] $produses
 */
class Categorii extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categorii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nume', 'descriere'], 'required'],
            [['parinte'], 'integer'],
            [['nume'], 'string', 'max' => 100],
            [['descriere'], 'string', 'max' => 200],
            [['nume', 'parinte'], 'unique', 'targetAttribute' => ['nume', 'parinte']],
            [['valid'], 'boolean'],
            [['parinte'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['parinte' => 'id']],
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
            'descriere' => 'Descriere',
            'parinte' => 'Parinte',
            'valid' => 'Valid'
        ];
    }

    /**
     * Gets query for [[Categoriis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategoriis()
    {
        return $this->hasMany(Categorii::class, ['parinte' => 'id']);
    }

    /**
     * Gets query for [[Parinte0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParinte0()
    {
        return $this->hasOne(Categorii::class, ['id' => 'parinte']);
    }

    /**
     * Gets query for [[Produses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduses()
    {
        return $this->hasMany(Produse::class, ['categorie' => 'id']);
    }

    public function salveazaCategorie(){
        $transaction = Yii::$app->db->beginTransaction();
        $restaurant_categorie = new RestauranteCategorii();
        $save = $this->save();
        $restaurant_categorie->categorie = $this->id;
        $restaurant_categorie->data_ora = date('Y-m-d H:i:s');
        $idUserConectat = Yii::$app->user->id;
        $idRestaurant = RestauranteUser::findOne(['user' => $idUserConectat])->restaurant;
        $restaurant_categorie->restaurant = $idRestaurant;
        $save = $restaurant_categorie->save();
        if ($save){
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }
    
    public static function formatItemsArray($categorii)
    {
        $items = Categorii::getDropDownitems($categorii);
        $result = [];
        foreach ($items as $item) {
            foreach ($item as $idCategorie => $numeCategorie) {
                $result[$idCategorie] = $numeCategorie;
            }
        }
        return $result;
    }

    private static function getDropDownitems($categorii, $indent = '', $idParinte = null)
    {
        $items = [];
        foreach ($categorii as $categorie) {
            if($categorie->parinte == $idParinte) {
                $items[$categorie->id] = $categorie->nume;
            }
        }

        $result = [];
        foreach ($items as $id => $nume) {
//            if ($categorie->parinte = null) {
                $result[] = [$id => $indent.$nume];
//            }
            $result = array_merge($result , self::getDropDownitems($categorii , $indent.'-' , $id));
        }

        return $result;
    }
}
