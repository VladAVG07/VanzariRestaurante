<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sesiuni".
 *
 * @property int $id
 * @property int $user
 * @property string $data_ora_start
 * @property string|null $data_ora_sfarsit
 *
 * @property SesiuniProduse[] $sesiuniProduses
 * @property User $user0
 */
class Sesiuni extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sesiuni';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user'], 'integer'],
            [['data_ora_start', 'data_ora_sfarsit'], 'safe'],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::class, 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'data_ora_start' => 'Data Ora Start',
            'data_ora_sfarsit' => 'Data Ora Sfarsit',
        ];
    }

    /**
     * Gets query for [[SesiuniProduses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSesiuniProduses()
    {
        return $this->hasMany(SesiuniProduse::class, ['sesiune' => 'id']);
    }

    /**
     * Gets query for [[User0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(\common\models\User::class, ['id' => 'user']);
    }
}
