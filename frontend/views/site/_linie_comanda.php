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
    <div class="col-md-6">
        <span><?=$model->denumire?></span>
    </div>
    <div class="col-md-2">
        <span><?=$model->cantitate?></span>
    </div>
    <div class="col-md-4">
        <span class='price'><?=$model->pret?> RON</span>
    </div>
</div>