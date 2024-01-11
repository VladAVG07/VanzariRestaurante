<?php

echo yii\widgets\ListView::widget([
    'layout' => '{items}',
    'dataProvider' => $comenziLiniiDataProvider,
    'itemView' => '_linie_comanda',
]);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

