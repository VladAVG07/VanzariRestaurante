<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo yii\widgets\ListView::widget([
    'layout' => '{items}',
    'id'=>$categorie,
    'dataProvider' => $dataProvider,
    'itemView' => '_product',
]);
