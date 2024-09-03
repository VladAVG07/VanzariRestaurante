<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 */



/* @var $model \backend\models\ComenziLinii */
?>

<div class="cart-single-meal" style="">
    <div class="cart-row" data-restaurant="1" id="23">
        <span class="cart-meal-amount"><span class="amount"><?=$model->cantitate?></span>x</span>
        <span class="cart-meal-name"><?=$model->produs0->nume?></span>
        <div class="cart-meal-edit-buttons">
            <button class="remove edit-delete" onclick="">-</button>
            <button class="add edit-delete" onclick="basket.increaseProduct(event, $(event.target).closest('.cart-row').data('id'));">+</button>
        </div>
        <span class="cart-meal-price"><span class="price"><?=$model->getTotal()?></span> lei</span>
        <button class="cart-delete-button"><i class="fas fa-trash-alt" style="color: #ff0000;"></i></button>
    </div>
<!--    <div class="cart-row" id="OR10P317Q-00QP3QQPON">
        <span class="cart-meal-comment grey" style="display:none"></span>
    </div>-->
</div>