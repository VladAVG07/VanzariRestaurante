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
class Categorii extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'categorii';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
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
    public function attributeLabels() {
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
    public function getCategoriis() {
        return $this->hasMany(Categorii::class, ['parinte' => 'id']);
    }

    /**
     * Gets query for [[Parinte0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParinte0() {
        return $this->hasOne(Categorii::class, ['id' => 'parinte']);
    }

    /**
     * Gets query for [[Produses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduses() {
        return $this->hasMany(Produse::class, ['categorie' => 'id']);
    }

    public static function getChildren($parent_id) {
        $items = [];
        $children = self::find()->where(['parinte' => $parent_id])
                        ->orderBy('nume')->all();
        foreach ($children as $child) {
            $items[$child->id] = $child->nume;
        }
        return $items;
    }

    public static function getParents($parent_id = null) {
        $items = [];
        $parents = self::find()->where(['parinte' => $parent_id])
                        ->orderBy('nume')->all();
        foreach ($parents as $parent) {
            $items['label'] = array_merge($items , [$parent->nume => self::getChildren($parent->id)]);
        }
        return $items;
    }

}
