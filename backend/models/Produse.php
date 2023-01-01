<?php

namespace backend\models;

use Yii;
use yii\db\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "produse".
 *
 * @property int $id
 * @property int $categorie
 * @property int $cod_produs
 * @property string $nume
 * @property string $descriere
 * @property string $data_productie
 *
 * @property Categorii $categorie0
 * @property PreturiProduse[] $preturiProduses
 * @property Stocuri[] $stocuris
 */
class Produse extends \yii\db\ActiveRecord
{
    public $pret;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'produse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categorie', 'cod_produs', 'nume', 'descriere', 'data_productie'], 'required'],
            [['categorie', 'cod_produs'], 'integer'],
            [['data_productie'], 'safe'],
            [['nume'], 'string', 'max' => 100],
            [['descriere'], 'string', 'max' => 200],
            [['cod_produs'], 'unique'],
            [['categorie'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['categorie' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'categorie' => 'Categorie',
            'cod_produs' => 'Cod Produs',
            'nume' => 'Nume',
            'descriere' => 'Descriere',
            'data_productie' => 'Data Productie',
        ];
    }

    /**
     * Gets query for [[Categorie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorie0()
    {
        return $this->hasOne(Categorii::class, ['id' => 'categorie']);
    }

    /**
     * Gets query for [[PreturiProduses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreturiProduses()
    {
        return $this->hasMany(PreturiProduse::class, ['produs' => 'id']);
    }

    /**
     * Gets query for [[Stocuris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocuris()
    {
        return $this->hasMany(Stocuri::class, ['produs' => 'id']);
    }

    public function getPretCurent() {
        return $this->getPreturiProduses()->where('valid = 1')->one();
    }

    public function saveOrUpdateWithPret($data , $formName = null) {

        $transaction = Yii::$app->db->beginTransaction();
        $modelPret = new PreturiProduse();

        try {
            if ($this->load($data , $formName) && $this->save()) {
                if($this->preturiProduses) {
                    $pretVechi = $this->getPretCurent();
                    $pretVechi->valid = 0;
                    $pretVechi->data_sfarsit = new \yii\db\Expression('NOW()');
                    $pretVechi->save();
                }
                $modelPret->load($data , $formName);
                $modelPret->valid = 1;
                $modelPret->produs = $this->id;
                if($modelPret->save()) {
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            return false;
        }
    }

}
