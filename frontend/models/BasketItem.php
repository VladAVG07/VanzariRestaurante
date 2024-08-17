<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class BasketItem extends Model{
    public $idProdus;
    public $denumire;
    public $pret;
    public $cantitate;
    public $pretFinal;
    public $produseDetalii;
    public $pdId;
    
}