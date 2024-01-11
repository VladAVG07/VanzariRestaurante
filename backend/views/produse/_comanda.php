<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$style = <<< CSS
.chenar {
    border: 1px solid #ebebeb;
    border-radius: 2px;
    margin:4px;
}
.continut {
    padding:3px;
}
CSS;
$this->registerCss($style);

foreach ($model->comenziLiniis as $produs){
    $formattedStrings[] = "{$produs->produs0->nume}";
}
$resultString = implode(', ', $formattedStrings);   

?>
    <div class="chenar">
        <div class="continut">
            <div><i class="fas fa-map-marker-alt"></i> <?= $model->adresa ?></div>
            <i class="fas fa-utensils"> Lista produse</i>
            <div><?= $resultString?></div>
        </div>
    </div>

