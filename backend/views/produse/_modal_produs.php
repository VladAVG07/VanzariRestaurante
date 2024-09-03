<?php

use yii\helpers\Html;

$produs = \backend\models\Produse::findOne(['id' => $id]);
$detaliiProdus = $produs->produseDetalii;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="meal-json">
        <?= \yii\helpers\Json::encode($produs->toArray()) ?>
    </div>
<div style="margin-bottom:20px">
    
    <?php
    foreach ($detaliiProdus as $detaliu) {
        ?>
        <div>
            <input type="radio" data-id=<?=$detaliu->id?> pret="<?=$detaliu->pret?>" id="<?= $detaliu->descriere?>" name="marime" style="transform: none;width: auto;height: auto;">
            <label for="<?=$detaliu->descriere?>"><?= $detaliu->descriere . ' - ' . $detaliu->pret . ' Lei' ?></label><br>
        </div>

        <?php
    }
    ?>
</div>
<?= Html::button('Adaugă în coș', ['class' => ['btn', 'btn-primary p-3 px-xl-4 py-xl-3 buton-add'], 'data-dismiss' => 'modal', 'style' => 'align-item:right;width:100%']) ?>

