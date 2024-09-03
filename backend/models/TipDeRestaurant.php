<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tipuri_de_restaurante".
 *
 * @property int $id
 * @property string $nume
 *
 * @property Restaurante[] $restaurantes
 */
class TipDeRestaurant extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipuri_de_restaurante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nume'], 'required'],
            [['nume'], 'string', 'max' => 255],
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
     * Gets query for [[Restaurantes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantes()
    {
        return $this->hasMany(Restaurante::class, ['tip_restaurant' => 'id']);
    }
}
