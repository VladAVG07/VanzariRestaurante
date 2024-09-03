<?php

namespace backend\models;

use backend\services\FileUploadService;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "restaurante".
 *
 * @property int $id
 * @property int|null $tip_restaurant Tipul din care face parte restaurantul 
 * @property string $nume
 * @property string $cui
 * @property string $adresa
 * @property string $numar_telefon
 * @property stirng $poza_prezentare
 * @property SetariLivrare $setariLivrare
 * 
 * @property TipDeRestaurant $tipRestaurant
 * @property Categorii[] $categorii
 */
class Restaurante extends \yii\db\ActiveRecord
{

    public $email;
    public $parola;
    public $confirmareParola;
    public $imageFile; // Property to hold the uploaded file


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurante';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nume', 'cui', 'adresa', 'numar_telefon', 'email', 'parola', 'confirmareParola', 'tip_restaurant'], 'required'],
            ['parola', 'compare', 'compareAttribute' => 'confirmareParola'],
            [['nume'], 'string', 'max' => 100],
            [['cui'], 'string', 'max' => 12],
            [['adresa'], 'string', 'max' => 200],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['poza_prezentare'], 'string', 'max' => 150],
            [['numar_telefon'], 'string', 'max' => 12],
            [['tip_restaurant'], 'exist', 'skipOnError' => true, 'targetClass' => TipDeRestaurant::class, 'targetAttribute' => ['tip_restaurant' => 'id']],
        ];
    }

    public function salveazaRestaurant()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $newRestaurant = $this->isNewRecord;
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
        $fileName = Yii::$app->security->generateRandomString(32);
        $this->poza_prezentare = sprintf('%s/%s.%s', 'uploads/restaurante', $fileName, $this->imageFile->extension);
        $save = $this->save();
        if (!$newRestaurant) {
            $user = new \common\models\User();
            $user->username = $this->email;
            $user->email = $this->email;
            $user->password = $this->parola;
            $user->status = 10;
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            $save = $save && $user->save();
            $restaurantUser = new RestauranteUser();
            $restaurantUser->restaurant = $this->id;
            $restaurantUser->user = $user->id;
            $restaurantUser->valid = 1;
            $save = $save && $restaurantUser->save();
        }
        if ($save) {
            $filePath = FileUploadService::uploadFile($this->imageFile, 'uploads/restaurante', $fileName);

            if (!is_null($filePath)) {
                $transaction->commit();
                return true;
            }
        }
        $transaction->rollBack();
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nume' => 'Nume',
            'cui' => 'Cui',
            'adresa' => 'Adresa',
            'tip_restaurant' => 'Tip restaurant',
            'poza_prezentare' => 'Poza prezentare',
            'imageFile' => 'Poza prezentare',
            'numar_telefon' => 'Numar telefon',
        ];
    }
    /**
     * Gets query for [[TipRestaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipRestaurant()
    {
        return $this->hasOne(TipDeRestaurant::class, ['id' => 'tip_restaurant']);
    }

    /**
     * Gets query for [[SetariLivrares]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSetariLivrare()
    {
        return $this->hasOne(SetariLivrare::class, ['restaurant' => 'id']);
    }

    public function getCategorii()
    {
        return $this->hasMany(Categorii::class, ['id' => 'categorie'])
                    ->where(['valid'=>1])
                    ->viaTable('restaurante_categorii', ['restaurant' => 'id']);
    }
}
