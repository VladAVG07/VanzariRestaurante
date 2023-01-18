<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "comenzi".
 *
 * @property int $id
 * @property int $numar_comanda
 * @property int $utilizator
 * @property int $status
 * @property string $data_ora_creare
 * @property string $data_ora_finalizare
 * @property float $pret
 * @property float $tva
 * @property string $mentiuni
 * @property string $canal
 * @property string $mod_plata
 *
 * @property Bonuri $bonuri
 * @property ComenziLinii[] $comenziLiniis
 * @property Facturi $facturi
 * @property Produse[] $produses
 * @property ComenziDetalii $status0
 * @property User $utilizator0
 */
class Comenzi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comenzi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numar_comanda', 'utilizator', 'status', 'data_ora_creare', 'data_ora_finalizare', 'pret', 'tva', 'mentiuni', 'canal', 'mod_plata'], 'required'],
            [['numar_comanda', 'utilizator', 'status'], 'integer'],
            [['data_ora_creare', 'data_ora_finalizare'], 'safe'],
            [['pret', 'tva'], 'number'],
            [['mentiuni'], 'string', 'max' => 255],
            [['canal', 'mod_plata'], 'string', 'max' => 20],
            [['utilizator'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['utilizator' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => ComenziDetalii::class, 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numar_comanda' => 'Numar Comanda',
            'utilizator' => 'Utilizator',
            'status' => 'Status',
            'data_ora_creare' => 'Data Ora Creare',
            'data_ora_finalizare' => 'Data Ora Finalizare',
            'pret' => 'Pret',
            'tva' => 'Tva',
            'mentiuni' => 'Mentiuni',
            'canal' => 'Canal',
            'mod_plata' => 'Mod Plata',
        ];
    }

    
    public function saveComanda($data ,$formName = null) {
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            
            
            
        } catch (Exception $ex) {
            
        }
        
    }
    
    /**
     * Gets query for [[Bonuri]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBonuri()
    {
        return $this->hasOne(Bonuri::class, ['comanda' => 'id']);
    }

    /**
     * Gets query for [[ComenziLiniis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComenziLiniis()
    {
        return $this->hasMany(ComenziLinii::class, ['comanda' => 'id']);
    }

    /**
     * Gets query for [[Facturi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFacturi()
    {
        return $this->hasOne(Facturi::class, ['comanda' => 'id']);
    }

    /**
     * Gets query for [[Produses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduses()
    {
        return $this->hasMany(Produse::class, ['id' => 'produs'])->viaTable('comenzi_linii', ['comanda' => 'id']);
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(ComenziDetalii::class, ['id' => 'status']);
    }

    /**
     * Gets query for [[Utilizator0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUtilizator0()
    {
        return $this->hasOne(User::class, ['id' => 'utilizator']);
    }
}
