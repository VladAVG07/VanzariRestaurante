<?php

namespace backend\models;

use backend\services\FileUploadService;
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
 * @property string $image_file
 * @property integer $ordine
 * @property float|null $pretFinal
 * 
 * @property Categorii $categorie0
 * @property PreturiProduse[] $preturiProduses
 * @property Stocuri[] $stocuris
 * @property ProduseDetalii[] $produseDetalii
 */
class Produse extends \yii\db\ActiveRecord
{

    public $pret;
    public $dataInceputPret;
    public $dataSfarsitPret;
    public $imageFile;
    public $imagePreview;
    public $tip_produs;
    public $produse_detalii = [];
    public $imageRemoved = 0;

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
            [['categorie', 'nume', 'descriere', 'stocabil', 'disponibil', 'tip_produs'], 'required'],
            [['categorie', 'cod_produs', 'valid', 'alerta_stoc', 'pret', 'imageRemoved', 'ordine'], 'integer'],
            [['data_productie', 'dataInceputPret', 'dataSfarsitPret', 'pret', 'alerta_stoc', 'imageFile', 'image_file'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['pret_curent', 'pret'], 'number'],
            [['nume'], 'string', 'max' => 100],
            [['descriere'], 'string', 'max' => 200],
            // [['cod_produs'], 'unique'],
            [['categorie'], 'exist', 'skipOnError' => true, 'targetClass' => Categorii::class, 'targetAttribute' => ['categorie' => 'id']],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->data_productie = date('Y-m-d');
                $this->cod_produs = round(microtime(true) * 1000);
            }
            return true;
        }
        return false;
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
            'pret_curent' => 'Preț',
            'pret' => 'Preț',
            'disponibil' => 'Activ',
            'valid' => 'Activ',
            'stocabil' => 'Stocabil',
            'image_file' => 'Imagine produs',
            'imageFile' => 'Imagine produs',
            'tip_produs' => 'Tip produs',
        ];
    }

    public function upload($numeImagine)
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('uploads/' . $numeImagine);
            return true;
        } else {
            return false;
        }
    }
    public function afterFind()
    {
        parent::afterFind();
        
        // Custom logic here, for example, modifying attribute values
        $detalii = $this->produseDetalii;

        $pret = sprintf('%s', $detalii[0]->pret);
        if (count($detalii) > 1) {
            $pret = sprintf('%s - %s', $detalii[0]->pret, $detalii[count($detalii) - 1]->pret);
        }
        return $pret;
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
     * Gets query for [[ProduseDetalii]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduseDetalii()
    {
        return $this->hasMany(ProduseDetalii::class, ['produs' => 'id'])->where(['IS', 'data_sfarsit', NULL])->orderBy(['pret' => SORT_ASC]);
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

    public function getPretCurent()
    {
        return $this->getPreturiProduses()->where('valid = 1')->one();
    }

    public function getPretText()
    {
        $detalii = $this->produseDetalii;

        $pret = sprintf('%s', $detalii[0]->pret);
        if (count($detalii) > 1) {
            $pret = sprintf('%s - %s', $detalii[0]->pret, $detalii[count($detalii) - 1]->pret);
        }
        return $pret;
    }

    public function getPretMeniu()
    {
        $detalii = $this->produseDetalii;
        return implode('/', array_map(function ($el) {
            return $el->pret;
        }, $detalii));
    }

    public function getProdusDetaliiDescriere()
    {
        $detalii = $this->produseDetalii;
        $text = implode('/', array_map(function ($el) {
            return $el->descriere;
        }, $detalii));
        if (strlen(trim($text)) == 0) {
            return '';
        }
        return sprintf('(%s)', $text);
    }

    public function saveProdus($numeImagine)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $pret = new PreturiProduse();
        if (!is_null($this->data_productie))
            $this->data_productie = date('Y-m-d', $this->data_productie);
        if ($this->stocabil == 0)
            $this->alerta_stoc = 0;
        if (!is_null($numeImagine)) {
            $this->image_file = sprintf('%s.%s', $numeImagine, $this->imageFile->extension);
        }
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
        $save = $save && $this->saveProdusDetalii();
        if ($save) {
            if (!is_null($this->imageFile)) {
                $filePath = FileUploadService::uploadFile($this->imageFile, 'uploads/produse', $numeImagine);
                if (!is_null($filePath)) {
                    $transaction->commit();
                    return true;
                }
            }
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    public function saveProdusDetalii()
    {
        foreach ($this->produse_detalii as $produsDetaliu) {
            $produsDetaliu->produs = $this->id;
            $saved = $produsDetaliu->save();
            if (!$saved) {
                $this->addErrors($produsDetaliu->errors);
                return false;
            }
        }
        return true;
    }

    public function getProdusAndCategorie()
    {
        if (!is_null($this->categorie0->parinte0)) {
            return sprintf('%s(%s)', $this->nume, $this->categorie0->nume);
        }
        return $this->nume;
    }

    public function updateProdus($numeImagine)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $pret = new PreturiProduse();
        $this->data_productie = date('Y-m-d', strtotime($this->data_productie));
        if ($this->stocabil == 0)
            $this->alerta_stoc = 0;
        $oldFile = sprintf('%s/web/uploads/produse/%s', Yii::getAlias('@backend'), $this->image_file);
        $oldMobileFile = sprintf('%s/web/uploads/produse/mobil/%s', Yii::getAlias('@backend'), $this->image_file);
        if (!is_null($numeImagine)) {
            $this->image_file = sprintf('%s.%s', $numeImagine, $this->imageFile->extension);
        } else {
            if ($this->imageRemoved)
                $this->image_file = null;
        }
        if ((!is_null($this->imageFile) || $this->imageRemoved) && file_exists($oldFile) && is_file($oldFile)) {
            unlink($oldFile);
            if (file_exists($oldMobileFile) && is_file($oldMobileFile))
                unlink($oldMobileFile);
        }

        $this->pret_curent = $this->pret;

        $save = $this->save();
        // if ($save) {
        //     if (is_null($this->pret) || empty($pret)) {

        //     } else {
        //         $save1 = true;
        //         $pretCurent = PreturiProduse::findOne(['produs' => $this->id, 'valid' => 1]);
        //         if (!is_null($this->dataInceputPret) && !empty($this->dataInceputPret)) {
        //             $pret->produs = $this->id;
        //             $pret->pret = $this->pret;
        //             $pret->data_inceput = date('Y-m-d', $this->dataInceputPret);
        //             if (!is_null($this->dataSfarsitPret) && !empty($this->dataSfarsitPret))
        //                 $pret->data_sfarsit = date('Y-m-d', $this->dataSfarsitPret);
        //             $pret->valid = 0;
        //             $query = PreturiProduse::find()->where([
        //                 'and', ['produs' => $this->id],
        //                 ['or', ['IS', 'data_sfarsit', NULL], ['>=', 'data_sfarsit', new \yii\db\Expression('now()')]]
        //             ]);
        //             $preturiViitoare = $query->all();
        //             foreach ($preturiViitoare as $pretViitor) {
        //                 if (!$save)
        //                     $save1 = false;
        //                 if ($pretViitor->valid != 1) {
        //                     if (!is_null($pretViitor->data_sfarsit) && !is_null($pret->data_sfarsit)) {
        //                         if (($pret->data_inceput >= $pretViitor->data_inceput && $pret->data_inceput <= $pretViitor->data_sfarsit) && $pret->data_sfarsit >= $pretViitor->data_sfarsit) {
        //                             $pretViitor->data_sfarsit = $pret->data_inceput;
        //                             $save = $pretViitor->save();
        //                         } else
        //                         if (($pret->data_inceput >= $pretViitor->data_inceput && $pret->data_inceput <= $pretViitor->data_sfarsit) && ($pret->data_sfarsit >= $pretViitor->data_inceput && $pret->data_sfarsit <= $pretViitor->data_sfarsit)) {
        //                             $pretViitor->data_sfarsit = $pret->data_inceput;
        //                             $save = $pretViitor->save();
        //                         } else
        //                         if ($pret->data_inceput <= $pretViitor->data_inceput && ($pret->data_sfarsit >= $pretViitor->data_inceput && $pret->data_sfarsit <= $pretViitor->data_sfarsit)) {
        //                             $pretViitor->data_inceput = $pret->data_sfarsit;
        //                             $save = $pretViitor->save();
        //                         } else
        //                         if ($pret->data_inceput <= $pretViitor->data_inceput && $pret->data_sfarsit >= $pretViitor->data_sfarsit) {
        //                             $save = $pretViitor->delete();
        //                         }
        //                     } else
        //                     if (is_null($pretViitor->data_sfarsit) && !is_null($pret->data_sfarsit)) {
        //                         if ($pret->data_inceput <= $pretViitor->data_inceput && $pret->data_sfarsit >= $pretViitor->data_inceput) {
        //                             $pretViitor->data_inceput = $pret->data_sfarsit;
        //                             $save = $pretViitor->save();
        //                         } else
        //                         if ($pret->data_inceput >= $pretViitor->data_inceput) {
        //                             $pretViitor->data_sfarsit = $pret->data_inceput;
        //                             $save = $pretViitor->save();
        //                         }
        //                     } else
        //                     if (is_null($pret->data_sfarsit)) {
        //                         if ($pret->data_inceput <= $pretViitor->data_inceput) {
        //                             $save = $pretViitor->delete();
        //                         } else
        //                         if ($pret->data_inceput >= $pretViitor->data_inceput) {
        //                             $pretViitor->data_sfarsit = $pret->data_inceput;
        //                             $save = $pretViitor->save();
        //                         }
        //                     }
        //                 }
        //             }
        //             if (strtotime($pret->data_inceput) <= time()) {
        //                 $pret->valid = 1;
        //                 $this->pret_curent = $pret->pret;
        //                 if (!is_null($pretCurent)) {
        //                     $pretCurent->valid = 0;
        //                     $pretCurent->data_sfarsit = date('Y-m-d');
        //                 }
        //             } else if (!is_null($pretViitor) && ($pret->data_inceput <= $pretCurent->data_sfarsit || is_null($pretCurent->data_sfarsit))) {
        //                 $pretCurent->data_sfarsit = $pret->data_inceput;
        //             }
        //            // $save1 = $this->save();
        //             $save1 = $pret->save();
        //             if (!is_null($pretCurent))
        //                 $save1 = $pretCurent->save();
        //             if ($save1) {
        //                 $transaction->commit();
        //                 return true;
        //             }
        //         }
        //     }
        // }
        $rowsAffected = ProduseDetalii::updateAll(['data_sfarsit' => date('Y-m-d')], ['produs' => $this->id]);
        $save = $save && $this->saveProdusDetalii();
        if ($save) {
            if (!is_null($numeImagine)) {
                $filePath = FileUploadService::uploadFile($this->imageFile, 'uploads/produse', $numeImagine);
                if (is_null($filePath)) {
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    public function saveOrUpdateWithPret($data, $formName = null)
    {

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
