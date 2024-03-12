<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "produse_detalii".
 *
 * @property int $id
 * @property int $produs
 * @property string|null $descriere
 * @property float|null $pret
 * @property int|null $disponibil
 * @property string $data_inceput
 * @property string|null $data_sfarsit
 *
 * @property Produse $produs0
 */
class ProduseDetalii extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produse_detalii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['produs'], 'required'],
            [['produs', 'disponibil'], 'integer'],
            [['pret'], 'number'],
            [['descriere'], 'string', 'max' => 150],
            [['produs'], 'exist', 'skipOnError' => true, 'targetClass' => Produse::class, 'targetAttribute' => ['produs' => 'id']],
        ];
    }

   public function beforeSave($insert)
   {
    if (parent::beforeSave($insert)) {
        if ($insert) {
            $this->data_inceput = date('Y-m-d');
        }
        else{
            $this->data_sfarsit=date('Y-m-d');
        }
        return true;
    }
    return false;
   }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'produs' => 'Produs',
            'descriere' => 'Descriere opțională',
            'pret' => 'Preț',
            'disponibil' => 'Disponibil',
        ];
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
