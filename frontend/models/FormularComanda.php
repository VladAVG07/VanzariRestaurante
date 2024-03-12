<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;

class FormularComanda extends Model
{

  public $nume;
  public $prenume;
  public $email;
  public $telefon;
  public $metodaPlata;

  public $strada;
  public $numar;
  public $oras;

  public $tipLocuinta;
  public $alteDetaliiReper;

  public $bloc;
  public $scara;
  public $apartament;
  public $interfon;


  public function rules()
  {
    $tipLocuintaId = Html::getInputId($this, 'tipLocuinta');
    return [
      [['nume', 'prenume', 'email', 'telefon', 'metodaPlata', 'strada', 'numar', 'oras', 'tipLocuinta'], 'required'],
      [['nume', 'prenume', 'email', 'telefon', 'strada', 'numar', 'oras'], 'trim'],
      [['metodataPlata', 'tipLocuinta'], 'integer'],
      ['bloc', 'required', 'when' => function ($model) {
        return $model->tipLocuinta == 2;
      }, 'whenClient' => "function (attribute, value) {
        return false;
    }"],
      // Other 
      ['email', 'email'],
      ['alteDetaliiReper', 'safe']
    ];
  }

  public function attributeLabels()
  {
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
    ];
  }
}
