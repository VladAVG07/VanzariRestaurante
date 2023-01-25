<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "comenzi_statusuri".
 *
 * @property int $id
 * @property string $nume
 *
 * @property ComenziDetalii[] $comenziDetaliis
 */
class ComenziStatusuri extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comenzi_statusuri';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nume'], 'required'],
            [['nume'], 'string', 'max' => 20],
            [['nume'], 'unique'],
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
        ];
    }

    /**
     * Gets query for [[ComenziDetaliis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComenziDetaliis()
    {
        return $this->hasMany(ComenziDetalii::class, ['status' => 'id']);
    }
}
