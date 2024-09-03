<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class Basket extends Model{
  public $basketItems=[];
  public $metodaPlata;
}