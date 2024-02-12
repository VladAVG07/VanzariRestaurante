<?php

namespace backend\models;

use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Expression;

/**
 * This is the model class for table "comenzi".
 *
 * @property int $id
 * @property int $numar_comanda
 * @property int $utilizator
 * @property int $status
 * @property string $data_ora_creare
 * @property string $data_ora_finalizare
 * @property float $pret
 * @property float $tva
 * @property string $mentiuni
 * @property string $canal
 * @property int $mod_plata
 * @property string $adresa
 * @property string $numar_telefon;
 *
 * @property Bonuri $bonuri
 * @property ComenziLinii[] $comenziLiniis
 * @property Facturi $facturi
 * @property Produse[] $produses
 * @property ComenziDetalii $status0
 * @property User $utilizator0
 * @property ModuriPlata $modPlata0
 */
class Comenzi extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'comenzi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['numar_comanda', 'data_ora_creare', 'tva', 'canal', 'mod_plata'], 'required'],
            [['numar_comanda', 'utilizator', 'status', 'mod_plata'], 'integer'],
            [['data_ora_creare', 'data_ora_finalizare', 'adresa', 'numar_telefon'], 'safe'],
            [['pret', 'tva'], 'number'],
            [['mentiuni'], 'string', 'max' => 255],
            [['canal'], 'string', 'max' => 20],
            [['utilizator'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['utilizator' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => ComenziDetalii::class, 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'numar_comanda' => 'Numar Comanda',
            'utilizator' => 'Utilizator',
            'status' => 'Status',
            'data_ora_creare' => 'Data Ora Creare',
            'data_ora_finalizare' => 'Data Ora Finalizare',
            'pret' => 'Pret',
            'tva' => 'Tva',
            'mentiuni' => 'Mentiuni',
            'canal' => 'Canal',
            'mod_plata' => 'Mod Plata',
        ];
    }

    public function adaugaComanda() {
        $transaction = Yii::$app->db->beginTransaction();
    }

    public function salveazaComanda($mentiuni, $adresa, $telefon) {
        $transaction = Yii::$app->db->beginTransaction();
        // \yii\helpers\VarDumper::dump("sunt aici");
        try {
            //  \yii\helpers\VarDumper::dump("sunt aici");
            $sesiune = Sesiuni::findOne(['user' => \Yii::$app->user->id, 'data_ora_sfarsit' => NULL]);
            // if(is_null($sesiune)){
            //     $sesiune=new Sesiuni();
            //     $sesiune->user=\Yii::$app->user->id;
            // }

            $sesiuniProduse = SesiuniProduse::find()->where(['sesiune' => $sesiune])->all();

            $pret = 0;
            $this->numar_comanda = 23;
            $this->canal = 'web';
            $this->data_ora_creare = new Expression('NOW()');
            $this->tva = 0.09;
            $this->utilizator = Yii::$app->user->id;
            $this->mentiuni = $mentiuni;
            $this->adresa = $adresa;
            $this->numar_telefon = $telefon;
            $this->mod_plata = 1;
            //          $this->status =3;
            $this->pret = 0;
            //        $this->mod_plata = 1;
//            $this->mentiuni = '';
            //  \yii\helpers\VarDumper::dump("sunt aici");
            if ($this->save()) {
                //   \yii\helpers\VarDumper::dump("sunt aici");
                // \yii\helpers\VarDumper::dump('AM ajuns aici');
                foreach ($sesiuniProduse as $sesiuneProdus) {
                    if ($sesiuneProdus->cantitate != 0) {
                        $modelComandaLinie = new ComenziLinii();
                        $modelComandaLinie->comanda = $this->id;
                        $modelComandaLinie->cantitate = $sesiuneProdus->cantitate;
                        $modelComandaLinie->produs = $sesiuneProdus->produs;
                        $modelComandaLinie->pret = Produse::find()->where(['id' => $sesiuneProdus->produs])->one()->pret_curent * $sesiuneProdus->cantitate;
                        $pret += $modelComandaLinie->pret;
                        if (Produse::findOne(['id' => $sesiuneProdus->produs])->stocabil) {
                            $query = Stocuri::find()->where(['and', ['produs' => $sesiuneProdus->produs], ['!=', 'cantitate_ramasa', 0]]);
                            $stocuri = $query->all();
                            $cantitate = $sesiuneProdus->cantitate;
                            foreach ($stocuri as $stoc) {
                                if ($cantitate != 0) {
                                    //\yii\helpers\VarDumper::dump($stoc);
                                    if ($cantitate > $stoc->cantitate_ramasa) {
                                        $cantitate -= $stoc->cantitate_ramasa;
                                        $stoc->cantitate_ramasa = 0;
                                    } else {
                                        $stoc->cantitate_ramasa -= $cantitate;
                                        $cantitate = 0;
                                    }
                                    $save = $stoc->save();
                                    if (!$save) {
                                        $transaction->rollBack();
                                        return false;
                                    }
                                } else
                                    break;
                            }
                            //   exit();
                        }
                        $modelComandaLinie->save();
                        // \yii\helpers\VarDumper::dump($modelComandaLinie->errors);
                    }
                }
                $this->refresh();
                $this->pret = $pret;
                $statusCurent = new ComenziDetalii();
                $statusCurent->comanda = $this->id;
                $statusCurent->status = 3;
                $statusCurent->data_ora_inceput = new Expression('NOW()');
                $statusCurent->save();


                $statusCurent->refresh();
                $this->status = $statusCurent->id;
//                $this->validate();
//                \yii\helpers\VarDumper::dump( $this->errors);
                //   \yii\helpers\VarDumper::dump("sunt aici");
                $sesiune->data_ora_sfarsit = new Expression('NOW()');
                if ($this->save() && $sesiune->save()) {
                    
                    //   \yii\helpers\VarDumper::dump("sunt aici");
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $ex) {
            //   \yii\helpers\VarDumper::dump($this->errors);
            $transaction->rollBack();
        }
//        \yii\helpers\VarDumper::dump(Yii::$app->request->post());
        return false;
    }

    public function saveComanda($data) {
        $transaction = Yii::$app->db->beginTransaction();
        // \yii\helpers\VarDumper::dump("sunt aici");
        try {
            //  \yii\helpers\VarDumper::dump("sunt aici");
            $pret = 0;
            $produseComanda = Yii::$app->request->post('produse');
            $this->numar_comanda = 23;
            $this->canal = 'web';
            $this->data_ora_creare = new Expression('NOW()');
            $this->tva = 0.09;
            $this->utilizator = Yii::$app->user->id;
            //          $this->status =3;
            $this->pret = 0;
            //        $this->mod_plata = 1;
//            $this->mentiuni = '';
            //  \yii\helpers\VarDumper::dump("sunt aici");
            if ($this->load(Yii::$app->request->post(), '') && $this->save()) {
                //   \yii\helpers\VarDumper::dump("sunt aici");
                // \yii\helpers\VarDumper::dump('AM ajuns aici');
                foreach ($produseComanda as $produs) {
                    $modelComandaLinie = new ComenziLinii();
                    $modelComandaLinie->comanda = $this->id;
                    $modelComandaLinie->cantitate = $produs['cantitate'];
                    $modelComandaLinie->produs = $produs['id'];
                    $modelComandaLinie->pret = Produse::find()->where(['id' => $produs['id']])->one()->pret_curent * $produs['cantitate'];
                    $pret += $modelComandaLinie->pret;
                    if (Produse::findOne(['id' => $produs['id']])->stocabil) {
                        $query = Stocuri::find()->where(['and', ['produs' => $produs['id']], ['!=', 'cantitate_ramasa', 0]]);
                        $stocuri = $query->all();
                        $cantitate = $produs['cantitate'];
                        foreach ($stocuri as $stoc) {
                            if ($cantitate != 0) {
                                //\yii\helpers\VarDumper::dump($stoc);
                                if ($cantitate > $stoc->cantitate_ramasa) {
                                    $cantitate -= $stoc->cantitate_ramasa;
                                    $stoc->cantitate_ramasa = 0;
                                } else {
                                    $stoc->cantitate_ramasa -= $cantitate;
                                    $cantitate = 0;
                                }
                                $save = $stoc->save();
                                if (!$save) {
                                    $transaction->rollBack();
                                    return false;
                                }
                            } else
                                break;
                        }
                        //   exit();
                    }
                    $modelComandaLinie->save();
                    // \yii\helpers\VarDumper::dump($modelComandaLinie->errors);
                }
                $this->refresh();
                $this->pret = $pret;
                $statusCurent = new ComenziDetalii();
                $statusCurent->comanda = $this->id;
                $statusCurent->status = 3;
                $statusCurent->data_ora_inceput = new Expression('NOW()');
                $statusCurent->save();


                $statusCurent->refresh();
                $this->status = $statusCurent->id;
//                $this->validate();
//                \yii\helpers\VarDumper::dump( $this->errors);
                //   \yii\helpers\VarDumper::dump("sunt aici");
                if ($this->save()) {
                    //   \yii\helpers\VarDumper::dump("sunt aici");
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $ex) {
            //   \yii\helpers\VarDumper::dump($this->errors);
            $transaction->rollBack();
        }
//        \yii\helpers\VarDumper::dump(Yii::$app->request->post());
        return false;
    }

    public function adaugareProdusComanda($id) {
        $transaction = Yii::$app->db->beginTransaction();
        $comanda = $this->findOne(['id' => $id]);
        $id_produs = Yii::$app->request->post('id_produs');
        $cantitate = Yii::$app->request->post('cantitate');

        try {
            if (!is_null(ComenziLinii::findOne(['comanda' => $id, 'produs' => $id_produs]))) {
                $comandaLinie = ComenziLinii::findOne(['comanda' => $id, 'produs' => $id_produs]);
                $comandaLinie->cantitate += $cantitate;
                $comandaLinie->pret += Produse::find()->where(['id' => $id_produs])->one()->pret_curent * $cantitate;
            } else {
                $comandaLinie = new ComenziLinii();
                $comandaLinie->comanda = $comanda->id;
                $comandaLinie->produs = $id_produs;
                $comandaLinie->cantitate = $cantitate;
                $comandaLinie->pret = Produse::find()->where(['id' => $id_produs])->one()->pret_curent * $cantitate;
            }

            if (Produse::findOne(['id' => $id_produs])->stocabil) {
                $query = Stocuri::find()->where(['and', ['produs' => $id_produs], ['!=', 'cantitate_ramasa', 0]]);
                $stocuri = $query->all();
                $c = $cantitate;
                foreach ($stocuri as $stoc) {
                    if ($c != 0) {
                        //\yii\helpers\VarDumper::dump($stoc);
                        if ($c > $stoc->cantitate_ramasa) {
                            $c -= $stoc->cantitate_ramasa;
                            $stoc->cantitate_ramasa = 0;
                        } else {
                            $stoc->cantitate_ramasa -= $c;
                            $c = 0;
                        }
                        $save = $stoc->save();
                        if (!$save) {
                            $transaction->rollBack();
                            return false;
                        }
                    } else
                        break;
                }
            }

            $comanda->pret += Produse::find()->where(['id' => $id_produs])->one()->pret_curent * $cantitate;

            $statusVechi = ComenziDetalii::findOne(['id' => $comanda->status]);
            $statusVechi->data_ora_sfarsit = new Expression('NOW()');
            $statusVechi->save();

            $statusNou = new ComenziDetalii();
            $statusNou->comanda = $comanda->id;
            $statusNou->data_ora_inceput = new Expression('NOW()');
            $statusNou->status = $statusVechi->status;
            $statusNou->detalii = 'Sistem: Au fost adaugate la comanda ' . $cantitate . " " . Produse::find()->where(['id' => $id_produs])->one()->nume;
            $statusNou->save();

            $comanda->status = $statusNou->id;
            if ($comandaLinie->save() && $comanda->save()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
    }

    public function stergereProdusComanda($id) {
        $transaction = Yii::$app->db->beginTransaction();
        $comanda = $this->findOne(['id' => $id]);
        $id_produs = Yii::$app->request->post('id_produs');
        $cantitate = Yii::$app->request->post('cantitate');

        try {
            $comandaLinie = ComenziLinii::findOne(['comanda' => $id, 'produs' => $id_produs]);
            if ($cantitate != $comandaLinie->cantitate) {
                $comandaLinie = ComenziLinii::findOne(['comanda' => $id, 'produs' => $id_produs]);
                $comandaLinie->cantitate -= $cantitate;
                $comandaLinie->pret -= Produse::find()->where(['id' => $id_produs])->one()->pret_curent * $cantitate;
                $comandaLinie->save();
            } else {
                $comandaLinie->delete();
            }

            if (Produse::findOne(['id' => $id_produs])->stocabil) {
                $query = Stocuri::find()->where(['produs' => $id_produs])->andWhere(['<>', 'cantitate_ramasa', new Expression('cantitate')]); //Stocuri::find()->where(['and', ['produs' => $id_produs], ['!=', 'cantitate_ramasa', 'cantitate']]);
                //$query = Stocuri::find()->where(['produs' => $id_produs])->andWhere(['!=', 'cantitate_ramasa', 'cantitate']);
                $stocuri = $query->all();
//                \yii\helpers\VarDumper::dump($stocuri);
//                exit();
                $c = $cantitate;
                foreach (array_reverse($stocuri) as $stoc) {
                    if ($c != 0) {
                        if ($c > $stoc->cantitate - $stoc->cantitate_ramasa) {
                            $c -= $stoc->cantitate - $stoc->cantitate_ramasa;
                            $stoc->cantitate_ramasa = $stoc->cantitate;
                        } else {
                            $stoc->cantitate_ramasa += $c;
                            $c = 0;
                        }
                        $save = $stoc->save();
                        if (!$save) {
                            $transaction->rollBack();
                            return false;
                        }
                    } else
                        break;
                }
            }

            $comanda->pret -= Produse::find()->where(['id' => $id_produs])->one()->pret_curent * $cantitate;

            $statusVechi = ComenziDetalii::findOne(['id' => $comanda->status]);
            $statusVechi->data_ora_sfarsit = new Expression('NOW()');
            $statusVechi->save();

            $statusNou = new ComenziDetalii();
            $statusNou->comanda = $comanda->id;
            $statusNou->data_ora_inceput = new Expression('NOW()');
            $statusNou->status = $statusVechi->status;
            $statusNou->detalii = 'Sistem: Au fost eliminate din comanda ' . $cantitate . " " . Produse::find()->where(['id' => $id_produs])->one()->nume;
            $statusNou->save();

            $comanda->status = $statusNou->id;
            if ($comanda->save()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
    }

    public function changeStatus($id) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $comanda = $this->findOne(['id' => $id]);
//            \yii\helpers\VarDumper::dump($comanda->id);
            $statusVechi = ComenziDetalii::findOne(['id' => $comanda->status]);
            //    \yii\helpers\VarDumper::dump($statusVechi->id);
            $statusVechi->data_ora_sfarsit = new Expression('NOW()');
            $statusVechi->save();

            $statusNou = new ComenziDetalii();
            $statusNou->comanda = $comanda->id;
            //   $statusNou->status = ComenziStatusuri::findOne(['id' => Yii::$app->request->post('id_status')]);
            $statusNou->status = Yii::$app->request->post('id_status');
            if (Yii::$app->request->post('id_status') == 7)
                $comanda->data_ora_finalizare = new Expression('NOW()');
            $detalii = Yii::$app->request->post('detalii');
            if (is_null($detalii) || empty($detalii)) {
                $statusNou->detalii = 'Sistem: Status schimbat din (' . $statusVechi->status0->nume . ') in (' . $statusNou->status0->nume . ')';
            } else
                $statusNou->detalii = $detalii;
            //    \yii\helpers\VarDumper::dump($statusNou->status->id);
            $statusNou->data_ora_inceput = new Expression('NOW()');
            //   \yii\helpers\VarDumper::dump("sunt aici");
            $statusNou->save();
            $statusNou->refresh();
            //  \yii\helpers\VarDumper::dump($statusNou->errors);
            //  \yii\helpers\VarDumper::dump($statusNou->id);
            //   \yii\helpers\VarDumper::dump($comanda->status);
            //    \yii\helpers\VarDumper::dump(' din in ');
            $comanda->status = $statusNou->id;
            //    \yii\helpers\VarDumper::dump("sunt aici");
            if ($comanda->save()) {

                $transaction->commit();
                //    \yii\helpers\VarDumper::dump($comanda->status0->status0->nume);
                return true;
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
    }

    public function changeMetodaPlata($id) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $comanda = $this->findOne(['id' => $id]);
            $comanda->mod_plata = Yii::$app->request->post('id_mod_plata');
            if ($comanda->save()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Gets query for [[Bonuri]].
     *
     * @return ActiveQuery
     */
    public function getBonuri() {
        return $this->hasOne(Bonuri::class, ['comanda' => 'id']);
    }

    /**
     * Gets query for [[ComenziLiniis]].
     *
     * @return ActiveQuery
     */
    public function getComenziLiniis() {
        return $this->hasMany(ComenziLinii::class, ['comanda' => 'id']);
    }

    /**
     * Gets query for [[Facturi]].
     *
     * @return ActiveQuery
     */
    public function getFacturi() {
        return $this->hasOne(Facturi::class, ['comanda' => 'id']);
    }

    /**
     * Gets query for [[Produses]].
     *
     * @return ActiveQuery
     */
    public function getProduses() {
        return $this->hasMany(Produse::class, ['id' => 'produs'])->viaTable('comenzi_linii', ['comanda' => 'id']);
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return ActiveQuery
     */
    public function getStatus0() {
        return $this->hasOne(ComenziDetalii::class, ['id' => 'status']);
    }

    /**
     * Gets query for [[Utilizator0]].
     *
     * @return ActiveQuery
     */
    public function getUtilizator0() {
        return $this->hasOne(User::class, ['id' => 'utilizator']);
    }

    /**
     * Gets query for [[ModPlata0]].
     *
     * @return ActiveQuery
     */
    public function getModPlata0() {
        return $this->hasOne(ModuriPlata::class, ['id' => 'mod_plata']);
    }

    public static function getComenzi($telefon) {
        $userId = \Yii::$app->user->id;
        $restaurant = RestauranteUser::find()->where(['user' => $userId])->one();
        return Comenzi::find()
                        ->innerJoin('user u', 'comenzi.utilizator = u.id')
                        ->innerJoin('restaurante_user ru', 'ru.user=u.id')
                        ->where(['numar_telefon' => $telefon, 'ru.restaurant' => $restaurant->restaurant])->orderBy(['data_ora_creare' => SORT_DESC]);
    }

}
