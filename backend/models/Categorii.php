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
 * @property int $ordine 
 * @property boolean $valid
 *
 * @property Categorii[] $categoriis
 * @property Categorii $parinte0
 * @property Produse[] $produses
 * @property CategoriiAsociate[] $categoriiAsociate
 */
class Categorii extends \yii\db\ActiveRecord {

    public $categorii_asociate = [];

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
            [['parinte', 'ordine'], 'integer'],
            [['categorii_asociate'], 'safe'],
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

    public function salveazaCategorie() {
        $transaction = Yii::$app->db->beginTransaction();
        $restaurant_categorie = new RestauranteCategorii();
        $save = $this->save();
        $categoriiAsociate = CategoriiAsociate::findAll(['categorie' => $this->id]);
        if (!empty($categoriiAsociate)) {
            foreach ($categoriiAsociate as $curenta) {
                if (!$curenta->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }
        }
        if (!empty($this->categorii_asociate)) {
            foreach ($this->categorii_asociate as $idCategorie) {
                $categorieAsociata = new CategoriiAsociate();
                $categorieAsociata->categorie = $this->id;
                $categorieAsociata->categorie_asociata = $idCategorie;
                $categorieAsociata->disponibil = 1;
                if (!$categorieAsociata->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }
        }
        $restaurant_categorie->categorie = $this->id;
        $restaurant_categorie->data_ora = date('Y-m-d H:i:s');
        $idUserConectat = Yii::$app->user->id;
        $idRestaurant = RestauranteUser::findOne(['user' => $idUserConectat])->restaurant;
        $restaurant_categorie->restaurant = $idRestaurant;
        $save = $restaurant_categorie->save();
        if ($save) {
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    public static function formatItemsArray($categorii) {
        $items = Categorii::getDropDownitems($categorii);
        $result = [];
        foreach ($items as $item) {
            foreach ($item as $idCategorie => $numeCategorie) {
                $result[$idCategorie] = $numeCategorie;
            }
        }
        return $result;
    }

    private static function getDropDownitems($categorii, $indent = '', $idParinte = null) {
        $items = [];
        foreach ($categorii as $categorie) {
            if ($categorie->parinte == $idParinte) {
                $items[$categorie->id] = $categorie->nume;
            }
        }

        $result = [];
        foreach ($items as $id => $nume) {
//            if ($categorie->parinte = null) {
            $result[] = [$id => $indent . $nume];
//            }
            $result = array_merge($result, self::getDropDownitems($categorii, $indent . '-', $id));
        }

        return $result;
    }

    public static function getSubcategories($parentId = null) {
        return self::find()
                        ->where(['parinte' => $parentId, 'valid' => 1])
                        ->all();
    }

    /**
     * Get all categories with their subcategories recursively.
     * @param int|null $parentId The parent category ID, null for main categories.
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAllCategoriesWithSubcategories($parentId = null) {
        $categories = self::getSubcategories($parentId);

        foreach ($categories as &$category) {
            $category['subcategories'] = self::getAllCategoriesWithSubcategories($category->id);
        }

        return $categories;
    }

}
