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
 * @property float|null $pret_curent
 * @property int|null $valid
 * @property boolean $stocabil
 * @property int $alerta_stoc
 * @property boolean $disponibil
 *
 * @property Categorii $categorie0
 * @property PreturiProduse[] $preturiProduses
 * @property Stocuri[] $stocuris
 */
class Produse extends \yii\db\ActiveRecord {

    public $pret;
    public $dataInceputPret;
    public $dataSfarsitPret;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'produse';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['categorie', 'cod_produs', 'nume', 'descriere', 'data_productie', 'stocabil', 'disponibil'], 'required'],
            [['categorie', 'cod_produs', 'valid', 'alerta_stoc', 'pret'], 'integer'],
            [['data_productie', 'dataInceputPret', 'dataSfarsitPret', 'pret', 'alerta_stoc'], 'safe'],
            [['pret_curent', 'pret'], 'number'],
            [['nume'], 'string', 'max' => 100],
            [['descriere'], 'string', 'max' => 200],
            [['cod_produs'], 'unique'],
            [['categorie'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['categorie' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'categorie' => 'Categorie',
            'cod_produs' => 'Cod Produs',
            'nume' => 'Nume',
            'descriere' => 'Descriere',
            'data_productie' => 'Data Productie',
            'pret_curent' => 'Pret',
            'valid' => 'Valid',
            'stocabil' => 'Stocabil'
        ];
    }

    /**
     * Gets query for [[Categorie0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategorie0() {
        return $this->hasOne(Categorii::class, ['id' => 'categorie']);
    }

    /**
     * Gets query for [[PreturiProduses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPreturiProduses() {
        return $this->hasMany(PreturiProduse::class, ['produs' => 'id']);
    }

    /**
     * Gets query for [[Stocuris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocuris() {
        return $this->hasMany(Stocuri::class, ['produs' => 'id']);
    }

    public function getPretCurent() {
        return $this->getPreturiProduses()->where('valid = 1')->one();
    }

    public function saveProdus() {
        $transaction = \Yii::$app->db->beginTransaction();
        $pret = new PreturiProduse();
        $this->data_productie = date('Y-m-d', $this->data_productie);
        if ($this->stocabil == 0)
            $this->alerta_stoc = 0;
        $save = $this->save();
        if (!is_null($this->pret) && !empty($this->pret)) {
            if ($save) {
                $pret->produs = $this->id;
                $pret->pret = $this->pret;
                if (!is_null($this->dataInceputPret) && !empty($this->dataInceputPret))
                    $pret->data_inceput = date('Y-m-d', $this->dataInceputPret);
                if (!is_null($this->dataSfarsitPret) && !empty($this->dataSfarsitPret))
                    $pret->data_sfarsit = date('Y-m-d', $this->dataSfarsitPret);
                if ($this->dataInceputPret <= time() && ($this->dataSfarsitPret >= time() || is_null($this->dataSfarsitPret) || empty($this->dataSfarsitPret))) {
                    $pret->valid = 1;
                    $this->pret_curent = $this->pret;
                    $save = $this->save();
                } else
                    $pret->valid = 0;
                $save = $pret->save();
            }
        }
        if ($save) {
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    public function updateProdus() {
        $transaction = Yii::$app->db->beginTransaction();
        $pret = new PreturiProduse();
        $this->data_productie = date('Y-m-d', $this->data_productie);
        if ($this->stocabil == 0)
            $this->alerta_stoc = 0;
        $save = $this->save();
        if ($save) {
            if (is_null($this->pret) || empty($pret)) {
                $transaction->commit();
                return true;
            } else {
                $save1 = true;
                $pretCurent = PreturiProduse::findOne(['produs' => $this->id, 'valid' => 1]);
                if (!is_null($this->dataInceputPret) && !empty($this->dataInceputPret)) {
                    $pret->produs = $this->id;
                    $pret->pret = $this->pret;
                    $pret->data_inceput = date('Y-m-d', $this->dataInceputPret);
                    if (!is_null($this->dataSfarsitPret) && !empty($this->dataSfarsitPret))
                        $pret->data_sfarsit = date('Y-m-d', $this->dataSfarsitPret);
                    $pret->valid = 0;
                    $query = PreturiProduse::find()->where(['and', ['produs' => $this->id],
                        ['or', ['IS', 'data_sfarsit', NULL], ['>=', 'data_sfarsit', new \yii\db\Expression('now()')]]]);
                    $preturiViitoare = $query->all();
                    foreach ($preturiViitoare as $pretViitor) {
                        if (!$save)
                            $save1 = false;
                        if ($pretViitor->valid != 1) {
                            if (!is_null($pretViitor->data_sfarsit) && !is_null($pret->data_sfarsit)) {
                                if (($pret->data_inceput >= $pretViitor->data_inceput && $pret->data_inceput <= $pretViitor->data_sfarsit) && $pret->data_sfarsit >= $pretViitor->data_sfarsit) {
                                    $pretViitor->data_sfarsit = $pret->data_inceput;
                                    $save = $pretViitor->save();
                                } else
                                if (($pret->data_inceput >= $pretViitor->data_inceput && $pret->data_inceput <= $pretViitor->data_sfarsit) && ($pret->data_sfarsit >= $pretViitor->data_inceput && $pret->data_sfarsit <= $pretViitor->data_sfarsit)) {
                                    $pretViitor->data_sfarsit = $pret->data_inceput;
                                    $save = $pretViitor->save();
                                } else
                                if ($pret->data_inceput <= $pretViitor->data_inceput && ($pret->data_sfarsit >= $pretViitor->data_inceput && $pret->data_sfarsit <= $pretViitor->data_sfarsit)) {
                                    $pretViitor->data_inceput = $pret->data_sfarsit;
                                    $save = $pretViitor->save();
                                } else
                                if ($pret->data_inceput <= $pretViitor->data_inceput && $pret->data_sfarsit >= $pretViitor->data_sfarsit) {
                                    $save = $pretViitor->delete();
                                }
                            } else
                            if (is_null($pretViitor->data_sfarsit) && !is_null($pret->data_sfarsit)) {
                                if ($pret->data_inceput <= $pretViitor->data_inceput && $pret->data_sfarsit >= $pretViitor->data_inceput) {
                                    $pretViitor->data_inceput = $pret->data_sfarsit;
                                    $save = $pretViitor->save();
                                } else
                                if ($pret->data_inceput >= $pretViitor->data_inceput) {
                                    $pretViitor->data_sfarsit = $pret->data_inceput;
                                    $save = $pretViitor->save();
                                }
                            } else
                            if (is_null($pret->data_sfarsit)) {
                                if ($pret->data_inceput <= $pretViitor->data_inceput) {
                                    $save = $pretViitor->delete();
                                } else
                                if ($pret->data_inceput >= $pretViitor->data_inceput) {
                                    $pretViitor->data_sfarsit = $pret->data_inceput;
                                    $save = $pretViitor->save();
                                }
                            }
                        }
                    }
                    if (strtotime($pret->data_inceput) <= time()) {
                        $pret->valid = 1;
                        $this->pret_curent = $pret->pret;
                        if (!is_null($pretCurent)) {
                            $pretCurent->valid = 0;
                            $pretCurent->data_sfarsit = date('Y-m-d');
                        }
                    } else if (!is_null($pretViitor) && ($pret->data_inceput <= $pretCurent->data_sfarsit || is_null($pretCurent->data_sfarsit))) {
                        $pretCurent->data_sfarsit = $pret->data_inceput;
                    }
                    $save1 = $this->save();
                    $save1 = $pret->save();
                    if (!is_null($pretCurent))
                        $save1 = $pretCurent->save();
                    if ($save1) {
                        $transaction->commit();
                        return true;
                    }
                }
            }
        }
        $transaction->rollBack();
        return false;
    }

    public function saveOrUpdateWithPret($data, $formName = null) {

        $transaction = Yii::$app->db->beginTransaction();
        $pretNou = new PreturiProduse();

        try {
            if ($this->load($data, $formName)) {
                $pretNou->load($data, $formName);
                $pretNou->pret = $this->pret_curent;
                //    VarDumper::dump(strtotime($pretNou->data_inceput) . ' ' . time());
                //    exit();
//                if (strtotime($pretNou->data_inceput) <= time()) {
//                    if ($this->preturiProduses) {
//                        $pretVechi = $this->getPretCurent();
//                        $pretVechi->valid = 0;
//                        if ($pretVechi->data_sfarsit < $pretNou->data_sfarsit && $pretVechi->valid == 0)
//                            $pretVechi->data_sfarsit = new \yii\db\Expression('NOW()');
//                        $pretVechi->save();
//                    }
//                    $pretNou->valid = 1;
//                    $pretNou->produs = $this->id;
//                }else{
//                    $pretVechi = $this->getPretCurent();
//                    $this->pret_curent = $pretVechi->pret;
//                    $pretNou->valid = 0;
//                    $pretNou->produs = $this->id;
//                }

                $this->validate();
                $pretNou->validate();
                $this->addErrors($pretNou->errors);
                if ($pretNou->save() && $this->save()) {

                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $exception) {
            VarDumper::dump($this->errors);
            $transaction->rollBack();
            return false;
        }
    }

}
