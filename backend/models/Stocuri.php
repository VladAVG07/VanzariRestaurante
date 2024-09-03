<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stocuri".
 *
 * @property int $id
 * @property int $produs
 * @property int $cantitate
 * @property float $pret_per_bucata
 * @property date $data_cumparare
 * @property int $cantitate_ramasa
 *
 * @property Produse $produs0
 */
class Stocuri extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stocuri';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['produs', 'cantitate', 'pret_per_bucata', 'data_cumparare', 'cantitate_ramasa'], 'required'],
            [['produs', 'cantitate', 'cantitate_ramasa'], 'integer'],
            [['pret_per_bucata'], 'number'],
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
            'produs' => 'Produs',
            'cantitate' => 'Cantitate',
            'pret_per_bucata' => 'Pret per bucata',
            'data_cumparare' => 'Data cumparare',
        ];
    }

    public function saveStoc(){
        $transaction = Yii::$app->db->beginTransaction();
        $this -> data_cumparare = date('Y-m-d', time());
        $this->cantitate_ramasa = $this->cantitate;
        if ($this->save()){
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
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
}
