<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "comenzi_detalii".
 *
 * @property int $id
 * @property int $comanda
 * @property int $status
 * @property string $data_ora_inceput
 * @property string $data_ora_sfarsit
 * @property string $detalii
 *
 * @property Comenzi[] $comenzis
 * @property ComenziStatusuri $status0
 */
class ComenziDetalii extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comenzi_detalii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comanda', 'status', 'data_ora_inceput'], 'required'],
            [['comanda', 'status'], 'integer'],
            [['detalii'], 'string', 'max' => 255],
            [['data_ora_inceput', 'data_ora_sfarsit'], 'safe'],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => ComenziStatusuri::class, 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comanda' => 'Comanda',
            'status' => 'Status',
            'data_ora_inceput' => 'Data Ora Inceput',
            'data_ora_sfarsit' => 'Data Ora Sfarsit',
            'detalii' => 'Detalii',
        ];
    }

    /**
     * Gets query for [[Comenzis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComenzis()
    {
        return $this->hasMany(Comenzi::class, ['status' => 'id']);
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(ComenziStatusuri::class, ['id' => 'status']);
    }
}
