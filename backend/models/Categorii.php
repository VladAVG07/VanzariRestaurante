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

    public static function getDropDownItems($indent = ' ', $idParinte = null) {
        $items = [];
        $categorii = self::find()->where(['parinte' => $idParinte])
                ->all();

        foreach ($categorii as $categorie) {
            //$items[$categorie->id] = $indent.$categorie->nume;
            $items[] = [$categorie->id => $indent . $categorie->nume];
            $items = array_merge($items, self::getDropDownItems($indent.'-', $categorie->id));
        }
        
        return $items;
        //jumatate de problema rezolvata :)
        //te descurci acum ?
        //cred ca da =))ok, hai sa vedem...ma anunti, daca nu, o rezolvam impreuna :)
        //ok, multumesc, cu placere ;)
    }
    
    public static function formatItemsArray() {
        $items = Categorii::getDropDownItems();
        $result = [];
        foreach($items as $item) {
            foreach ($item as $idCategorie => $numeCategorie) {
                $result[$idCategorie] = $numeCategorie;
            }
        }
        return $result;
    }
}
