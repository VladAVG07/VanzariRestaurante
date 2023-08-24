<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "restaurante".
 *
 * @property int $id
 * @property string $nume
 * @property string $cui
 * @property string $adresa
 * @property string $numar_telefon
 */
class Restaurante extends \yii\db\ActiveRecord {

    public $email;
    public $parola;
    public $confirmareParola;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['nume', 'cui', 'adresa', 'numar_telefon', 'email', 'parola', 'confirmareParola'], 'required'],
            ['parola', 'compare', 'compareAttribute' => 'confirmareParola'],
            [['nume'], 'string', 'max' => 100],
            [['cui'], 'string', 'max' => 12],
            [['adresa'], 'string', 'max' => 200],
            [['numar_telefon'], 'string', 'max' => 12],
        ];
    }

    public function salveazaRestaurant() {
        $transaction = Yii::$app->db->beginTransaction();
        $user = new \common\models\User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->password = $this->parola;
        $user->status = 10;
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $save = $this->save();
        $save = $save && $user->save();
        $restaurantUser = new RestauranteUser();
        $restaurantUser->restaurant = $this->id;
        $restaurantUser->user = $user->id;
        $restaurantUser->valid = 1;
        $save = $save && $restaurantUser->save();
        if ($save){
            $transaction->commit();
            return true;
        }
        $transaction->rollBack();
        return false;           
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'nume' => 'Nume',
            'cui' => 'Cui',
            'adresa' => 'Adresa',
            'numar_telefon' => 'Numar Telefon',
        ];
    }

}
