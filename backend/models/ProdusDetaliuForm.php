<?php

namespace backend\models;

use Yii;
use yii\base\Model;

class ProdusDetaliuForm extends Model{
    public $descriere;
    public $pret;
    public $disponibil;

     /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pret'], 'required'],
            [['descriere','disponibil'],'safe'],
            [['produs', 'disponibil'], 'integer'],
            [['pret'], 'number'],
            [['descriere'], 'string', 'max' => 150],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'descriere' => 'Descriere',
        ];
    }
}