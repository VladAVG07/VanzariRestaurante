<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 */



/* @var \frontend\models\BasketItem $model*/
?>

<div class="d-flex align-items-center pt-2">
    <div class="col-md-6 col-6">
        <span><?=$model->denumire?></span>
    </div>
    <div class="col-md-3 col-3 text-center">
        <span><?=$model->cantitate?></span>
    </div>
    <div class="col-md-3 col-3 text-right">
        <span class='price'><?=$model->pret?></span>
    </div>
</div>