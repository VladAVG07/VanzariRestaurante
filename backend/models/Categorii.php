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

    use \kartik\tree\models\TreeTrait {
        isDisabled as parentIsDisabled; // note the alias
    }

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

    public static function getItems($indent = '', $parent_group = null) {
        $items = [];
        // for all childs of $parent_group (roots if $parent_group == null)
        $groups = self::find()->where(['parinte' => $parent_group])
                        ->orderBy('short_name')->all();
        foreach ($groups as $group) {
            // add group to items list 
            $items[$group->id] = $indent . $group->short_name;
            // recursively add children to the list with indent
            $items = array_merge($items, self::getItems($indent . ' ', $group->id));
        }
        return $items;
    }

}
