<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "stocuri".
 *
 * @property int $id
 * @property int $produs
 * @property int $cantitate
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
            [['produs', 'cantitate'], 'required'],
            [['produs', 'cantitate'], 'integer'],
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
