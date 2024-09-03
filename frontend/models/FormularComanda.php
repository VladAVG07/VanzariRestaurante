<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yii\db\Expression;
use common\models\User;

class FormularComanda extends Model {

    public $nume;
    public $prenume;
    public $email;
    public $telefon;
    public $metodaPlata;
    public $strada;
    public $numar;
    public $oras;
    public $tipLocuinta = 1;
    public $alteDetaliiReper;
    public $bloc;
    public $scara;
    public $apartament;
    public $interfon;
    public $oraLivrare;
    public $numarComanda;
    public $idComanda;

    public function rules() {
        $tipLocuintaId = Html::getInputName($this, 'tipLocuinta');
        return [
            [['nume', 'prenume', 'email', 'telefon', 'metodaPlata', 'strada', 'oras', 'tipLocuinta', 'oraLivrare'], 'required'],
            [['nume', 'prenume', 'email', 'telefon', 'strada', 'oras'], 'trim'],
            ['tipLocuinta', 'integer'],
            [
                'numar', 'required', 'when' => function ($model) {
                    return $model->tipLocuinta == 1;
                }, //'enableClientValidation' => false,
                'whenClient' => "function (attribute, value) {
        return $('input[name=\"$tipLocuintaId\"]:checked').val() == 1;
    }"
            ],
            [['metodaPlata', 'tipLocuinta'], 'integer'],
            [['bloc', 'scara', 'apartament'], 'required', 'when' => function ($model) {
                    return $model->tipLocuinta == 2;
                }, 'whenClient' => "function (attribute, value) {
        return $('input[name=\"$tipLocuintaId\"]:checked').val() == 2;
    }"],
            // Other 
            ['email', 'email'],
            [['alteDetaliiReper', 'interfon', 'idComanda'], 'safe']
        ];
    }

    public function attributeLabels() {
        return [
            'nume' => 'Nume',
            'prenume' => 'Prenume',
            'email' => 'Adresa de email',
            'telefon' => 'Telefon',
            'metodaPlata' => 'Metoda de plată',
            'oras' => 'Localitate',
            'numar' => 'Număr',
            'strada' => 'Strada',
            'tipLocuinta' => 'Tip livrare',
            'metodaPlata' => 'Metoda de plată a comenzii',
            'oraLivrare' => 'Când livrăm?'
        ];
    }

    public function getNumeComplet() {
        return sprintf('%s %s', $this->nume, $this->prenume);
    }

    public function getAdresaCompleta() {
        if ($this->tipLocuinta == 1) {
            return sprintf('Strada: %s, Numar: %s, Localitate: %s', $this->strada, $this->numar, $this->oras);
        } else {
            return sprintf(
                    'Strada: %s, Bloc: %s, Scara: %s, Apartament: %s, Interfon: %s, Localitate: %s', $this->strada, $this->bloc, $this->scara, $this->apartament, empty($this->interfon) ? '-' : $this->interfon, $this->oras
            );
        }
    }

    public function saveComanda($basket) {
        // var_dump($basket);
        //   exit();
        $transaction = Yii::$app->db->beginTransaction();
        $comanda = new \backend\models\Comenzi();
        if (Yii::$app->user->isGuest) {
            $userExistent = User::findOne(['email' => $this->email]);
            if (is_null($userExistent)) {
                $user = new User();
                $user->email = $this->email;
                $user->setPassword('12345678');
                $user->generateAuthKey();
                if (!$user->save()) {
                    var_dump($user->errors);
                    exit();
                    $transaction->rollBack();
                    return false;
                }
                $comanda->utilizator = $user->id;
            } else {
                $comanda->utilizator = $userExistent->id;
            }
        } else
            $comanda->utilizator = Yii::$app->user->id;
        $comanda->data_ora_creare = new Expression('NOW()');
        $comanda->adresa = $this->getAdresaCompleta();
        $comanda->numar_telefon = $this->telefon;
        $comanda->numar_comanda = 1;
        $comanda->canal = 'web';
        $comanda->mentiuni = $this->alteDetaliiReper;
        if ($comanda->save()) {
            $comanda->numar_comanda = $comanda->id;
            $pret = 0;
//            var_dump($basket->basketItems);
//            exit();
            foreach ($basket->basketItems as $produs) {
                $comandaLinie = new \backend\models\ComenziLinii();
                if ($produs->denumire == 'Cost livrare') {
                    $produsId = $produs->idProdus;
                } else {
                    $produsId = \backend\models\ProduseDetalii::findOne(['id' => $produs->idProdus])->produs;
                    $comandaLinie->produs_detaliu = $produs->idProdus;
                }
                $comandaLinie->comanda = $comanda->id;
                $comandaLinie->produs = $produsId;
                $comandaLinie->cantitate = $produs->cantitate;
                $comandaLinie->pret = $produs->pret;
                $pret += $produs->pret;
                
                $save = $comandaLinie->save();
                if (!$save) {
                    var_dump($comandaLinie->errors);
                    exit();
                    $transaction->rollBack();
                    return false;
                }
            }
            $comandaDetaliu = new \backend\models\ComenziDetalii();
            $comandaDetaliu->comanda = $comanda->id;
            $comandaDetaliu->status = 3;
            $comandaDetaliu->data_ora_inceput = new Expression('NOW()');
            $save = $comandaDetaliu->save();
            $comanda->pret = $pret;
            $comanda->tva = 0.09; //* $this->pret;
            $comanda->status = $comandaDetaliu->id;

            $restaurantComanda = new \backend\models\RestauranteComenzi();
            $restaurantComanda->restaurant = 10;
            $restaurantComanda->comanda = $comanda->id;
            $lastRow = \backend\models\RestauranteComenzi::find()
                    ->where(['restaurant' => 10])
                    ->orderBy(['id' => SORT_DESC])
                    ->one();
            if ($lastRow !== null) {
                $restaurantComanda->numar_comanda = $lastRow->numar_comanda + 1;
            } else {
                $restaurantComanda->numar_comanda = 1;
            }
            $comanda->numar_comanda = $restaurantComanda->numar_comanda;
            $this->numarComanda = $restaurantComanda->numar_comanda;

            if ($comanda->save() && $restaurantComanda->save() && $save) {
                $this->idComanda = $comanda->id;
                $transaction->commit();
                return true;
            }
        }
        var_dump($comanda->errors);
        exit();
        $transaction->rollBack();
        return false;
    }

    public function sendMail($basket) {
        $mailer = Yii::$app->mailer;
        $message = $mailer->compose(['html' => '@frontend/views/email/comandaEmail'], ['model' => $this, 'basket' => $basket])
                ->setFrom(['no-reply@diobistro.ro' => 'Dio Bistro']) // Set the "From" header
                //->setTextBody('Test body')
                ->setTo($this->email)
                ->setSubject('Comanda a fost primită!');
        try {
            // Send the email
            $mailer->send($message);
            //   echo "Email sent successfully.";
            return true;
        } catch (TransportExceptionInterface $e) {
            // Handle transport exception (e.g., connection error, SMTP authentication failure)
            //echo "Failed to send email: " . $e->getMessage();
            return false;
        } catch (\Exception $e) {
            // Handle other exceptions
            return false;
            //echo "An error occurred: " . $e->getMessage();
        }
        // $basket = \Yii::$app->session->get('basket');
        // return $this->render('mail_comanda', ['basket' => $basket]);
    }

}
