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
 * @property string $mod_plata
 *
 * @property Bonuri $bonuri
 * @property ComenziLinii[] $comenziLiniis
 * @property Facturi $facturi
 * @property Produse[] $produses
 * @property ComenziDetalii $status0
 * @property User $utilizator0
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
            [['numar_comanda', 'utilizator', 'status'], 'integer'],
            [['data_ora_creare', 'data_ora_finalizare'], 'safe'],
            [['pret', 'tva'], 'number'],
            [['mentiuni'], 'string', 'max' => 255],
            [['canal', 'mod_plata'], 'string', 'max' => 20],
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

    public function saveComanda($data) {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $pret = 0;
            $produseComanda = Yii::$app->request->post('produse');
            $this->numar_comanda = 23;
            $this->canal = 'web';
            $this->data_ora_creare = new Expression('NOW()');
            $this->tva = 0.09;
            $this->utilizator = Yii::$app->user->id;
            //          $this->status =3;
            $this->pret = 0;
//            $this->mod_plata = 'card';
//            $this->mentiuni = '';
            if ($this->load(Yii::$app->request->post(), '') && $this->save()) {
               // \yii\helpers\VarDumper::dump('AM ajuns aici');
                foreach ($produseComanda as $produs) {
                    $modelComandaLinie = new ComenziLinii();
                    $modelComandaLinie->comanda = $this->id;
                    $modelComandaLinie->cantitate = $produs['cantitate'];
                    $modelComandaLinie->produs = $produs['id'];
                    $modelComandaLinie->pret = Produse::find()->where(['id' => $produs['id']])->one()->pret_curent * $produs['cantitate'];
                    $pret += $modelComandaLinie->pret;
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
                if ($this->save()) {
                    $transaction->commit();
                    return true;
                }
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
        }
//        \yii\helpers\VarDumper::dump(Yii::$app->request->post());
        return false;
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
        //    \yii\helpers\VarDumper::dump($statusNou->status->id);
            $statusNou->data_ora_inceput = new Expression('NOW()');
            $statusNou->save();
            $statusNou->refresh();
          //  \yii\helpers\VarDumper::dump($statusNou->errors);
          //  \yii\helpers\VarDumper::dump($statusNou->id);
         //   \yii\helpers\VarDumper::dump($comanda->status);
        //    \yii\helpers\VarDumper::dump(' din in ');
            $comanda->status = $statusNou->id;
            if ($comanda->save()) {
                $transaction->commit();
               // \yii\helpers\VarDumper::dump($comanda->status);
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

}
