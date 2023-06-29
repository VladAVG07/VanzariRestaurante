<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "moduri_plata".
 *
 * @property int $id
 * @property string $nume
 *
 * @property Comenzi[] $comenzis
 */
class ModuriPlata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moduri_plata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nume'], 'required'],
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
        ];
    }

    /**
     * Gets query for [[Comenzis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComenzis()
    {
        return $this->hasMany(Comenzi::class, ['mod_plata' => 'id']);
    }
}
