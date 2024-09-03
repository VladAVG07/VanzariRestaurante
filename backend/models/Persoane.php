<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "persoane".
 *
 * @property int $id
 * @property int $numar_identificare
 * @property string $nume
 * @property string $prenume
 * @property int $functie_curenta
 *
 * @property FunctiiPersoane[] $functiiPersoanes
 * @property Functii $functie_curenta0
 */
class Persoane extends \yii\db\ActiveRecord {

    public $functie;
    public $dataInceputFunctie;
    public $dataSfarsitFunctie;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'persoane';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['numar_identificare', 'nume', 'prenume'], 'required'],
            [['numar_identificare', 'functie', 'functie_curenta'], 'integer'],
            [['nume', 'prenume'], 'string', 'max' => 50],
            [['dataSfarsitFunctie', 'dataInceputFunctie', 'functie'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'numar_identificare' => 'Numar Identificare',
            'nume' => 'Nume',
            'prenume' => 'Prenume',
            'functie_curenta' => 'Functie',
        ];
    }

    public function savePersoana() {
        $transaction = Yii::$app->db->beginTransaction();
        $functie = new FunctiiPersoane();
        $save = $this->save();
        if (!is_null($this->functie) && !empty($this->functie)) {
            if ($save) {
                $functie->persoana = $this->id;
                $functie->functie = $this->functie;
                if (!is_null($this->dataInceputFunctie) && !empty($this->dataInceputFunctie))
                    $functie->data_inceput = date('Y-m-d', $this->dataInceputFunctie);
                if (!is_null($this->dataSfarsitFunctie) && !empty($this->dataSfarsitFunctie))
                    $functie->data_sfarsit = date('Y-m-d', $this->dataSfarsitFunctie);
                if ($this->dataInceputFunctie <= time() && ($this->dataSfarsitFunctie >= time() || is_null($this->dataSfarsitFunctie) ||
                        empty($this->dataSfarsitFunctie))) {
                    $functie->valid = 1;
                    $this->functie_curenta = $this->functie;
                    $save = $this->save();
                } else
                    $functie->valid = 0;
                $save = $functie->save();
            }
        }
        if ($save) {
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    public function updatePersoana() {
        $transaction = Yii::$app->db->beginTransaction();
        $functie = new FunctiiPersoane();
        $save = $this->save();
        if ($save) {
            if (is_null($this->functie) || empty($this->functie)) {
                $transaction->commit();
                return true;
            } else {
                $save1 = true;
                $functieVeche = FunctiiPersoane::findOne(['persoana' => (int) $this->id, 'valid' => 1]);
                if (!is_null($this->dataInceputFunctie) && !empty($this->dataInceputFunctie)) {
                    $functie->persoana = $this->id;
                    $functie->functie = $this->functie;
                    $functie->data_inceput = date('Y-m-d', $this->dataInceputFunctie);
                    if (!is_null($this->dataSfarsitFunctie) && !empty($this->dataSfarsitFunctie))
                        $functie->data_sfarsit = date('Y-m-d', $this->dataSfarsitFunctie);
                    $functie->valid = 0;
                    $query = FunctiiPersoane::find()->where(['and', ['persoana' => $this->id],
                        ['or', ['IS', 'data_sfarsit', NULL], ['>=', 'data_sfarsit', new \yii\db\Expression('now()')]]]);
                    $functiiViitoare = $query->all();
                    foreach ($functiiViitoare as $functieViitoare) {
                        if (!$save)
                            $save1 = false;
                        if ($functieViitoare->valid != 1) {
                            if (!is_null($functieViitoare->data_sfarsit) && !is_null($functie->data_sfarsit)) {
                                if (($functie->data_inceput >= $functieViitoare->data_inceput && $functie->data_inceput <= $functieViitoare->data_sfarsit) && $functie->data_sfarsit >= $functieViitoare->data_sfarsit) {
                                    $functieViitoare->data_sfarsit = $functie->data_inceput;
                                    $save = $functieViitoare->save();
                                } else
                                if (($functie->data_inceput >= $functieViitoare->data_inceput && $functie->data_inceput <= $functieViitoare->data_sfarsit) && ($functie->data_sfarsit >= $functieViitoare->data_inceput && $functie->data_sfarsit <= $functieViitoare->data_sfarsit)) {
                                    $functieViitoare->data_sfarsit = $functie->data_inceput;
                                    $save = $functieViitoare->save();
                                } else
                                if ($functie->data_inceput <= $functieViitoare->data_inceput && ($functie->data_sfarsit >= $functieViitoare->data_inceput && $functie->data_sfarsit <= $functieViitoare->data_sfarsit)) {
                                    $functieViitoare->data_inceput = $functie->data_sfarsit;
                                    $save = $functieViitoare->save();
                                } else
                                if ($functie->data_inceput <= $functieViitoare->data_inceput && $functie->data_sfarsit >= $functieViitoare->data_sfarsit) {
                                    $save = $functieViitoare->delete();
                                }
                            } else
                            if (is_null($functieViitoare->data_sfarsit) && !is_null($functie->data_sfarsit)) {
                                if ($functie->data_inceput <= $functieViitoare->data_inceput && $functie->data_sfarsit >= $functieViitoare->data_inceput) {
                                    $functieViitoare->data_inceput = $functie->data_sfarsit;
                                    $save = $functieViitoare->save();
                                } else
                                if ($functie->data_inceput >= $functieViitoare->data_inceput) {
                                    $functieViitoare->data_sfarsit = $functie->data_inceput;
                                    $save = $functieViitoare->save();
                                }
                            } else
                            if (is_null($functie->data_sfarsit)) {
                                if ($functie->data_inceput <= $functieViitoare->data_inceput) {
                                    $save = $functieViitoare->delete();
                                } else
                                if ($functie->data_inceput >= $functieViitoare->data_inceput) {
                                    $functieViitoare->data_sfarsit = $functie->data_inceput;
                                    $save = $functieViitoare->save();
                                }
                            }
                        }
                    }
                    if (strtotime($functie->data_inceput) <= time()) {
                        $functie->valid = 1;
                        $this->functie_curenta = $functie->functie;
                        if (!is_null($functieVeche)) {
                            $functieVeche->valid = 0;
                            $functieVeche->data_sfarsit = date('Y-m-d');
                        }
                    } else if (!is_null($functieVeche) && ($functie->data_inceput <= $functieVeche->data_sfarsit || is_null($functieVeche->data_sfarsit))) {
                        $functieVeche->data_sfarsit = $functie->data_inceput;
                    }
                    $save1 = $this->save();
                    $save1 = $functie->save();
                    if (!is_null($functieVeche))
                        $save1 = $functieVeche->save();
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

    public function saveOrUpdateWithFunctie($idFP = null) {

//        \yii\helpers\VarDumper::dump($this->dataInceputFunctie);
//        exit();
        $transaction = Yii::$app->db->beginTransaction();
        $functieNoua = new FunctiiPersoane();
        $functieVeche = FunctiiPersoane::findOne(['id' => $idFP]);
        $save = $this->save();
        if (!is_null($this->dataInceputFunctie)) {
            $valid = 0;
            $schimbat = 0;
            if ($save) {
                if (strtotime($this->dataInceputFunctie) <= time() && (strtotime($this->dataSfarsitFunctie) >= time() || is_null($this->dataSfarsitFunctie))) {
                    $functieNoua->valid = 1;
                    $valid = 1;
                    if (!is_null($functieVeche)) {
                        $functieVeche->data_sfarsit = date('Y-m-d');

                        $functieVeche->valid = 0;
                        $schimbat = 1;
                    }
                } else
                    $functieNoua->valid = 0;
                if (!is_null($functieVeche))
                    if ($this->dataInceputFunctie <= $functieVeche->data_sfarsit && !$schimbat) {
                        $functieVeche->data_sfarsit = date('Y-m-d', $this->dataInceputFunctie);
                    }
                $functieNoua->functie = $this->functie;
                $functieNoua->persoana = $this->id;
                $functieNoua->data_inceput = date('Y-m-d', $this->dataInceputFunctie); //$this->dataInceputFunctie;
                if (!is_null($this->dataSfarsitFunctie) && !empty($this->dataSfarsitFunctie))
                    $functieNoua->data_sfarsit = date('Y-m-d', $this->dataSfarsitFunctie);
                $save = $functieNoua->save();
                if ($valid) {
                    $this->functie_curenta = $functieNoua->functie0->id;
                }
                if (!is_null($functieVeche))
                    $save = $functieVeche->save();
                $save = $this->save();
            }
        }
        if ($save) {
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;
    }

    /**
     * Gets query for [[FunctiiPersoanes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFunctiiPersoanes() {
        return $this->hasMany(FunctiiPersoane::class, ['persoana' => 'id']);
    }

}
