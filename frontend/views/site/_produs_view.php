<?php

use backend\models\Produse;
use yii\helpers\Html;
use frontend\themes\pizzagh\assets\PizzGhAsset;
use kartik\touchspin\TouchSpin;

$produs = Produse::findOne(['id' => $id]);
$imagine = 'backend/uploads/produse/' . $produs->image_file;
$assetDir = PizzGhAsset::register($this);
?>

<div class="d-block text menu-wrap align-items-center">
    <?php if (is_null($produs->image_file) || strlen(trim($produs->image_file)) === 0) { ?>
        <a class="menu-img img mb-4" href="#" style="background-image: url(<?= $assetDir->baseUrl ?>/images/no-photo.png);"></a>
    <?php } else { ?>
        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $imagine ?>);"></a>
    <?php } ?>
</div>
<div class="detalii-produs p-2">
    <h5><?= $produs->nume ?></h5>
    <p><?= $produs->descriere ?></p>
</div>
<div class="selecteaza-varianta p2 align-items-center">
    <div class="form-row justify-content-center">
        <div class="form-group">
            <div class="form-check">
                <input type="radio" id="tip1" name="tip">
                <label for="tip1">Medie (32cm - 300gr) - 32 RON</label>
            </div>
            <div class="form-check">
                <input type="radio" id="tip2" name="tip">
                <label for="tip2">Mare (40cm - 450gr) - 45 RON</label>
            </div>
            <div class="form-check">
                <input type="radio" id="tip3" name="tip">
                <label for="tip3">Family (55cm - 1000gr) - 75 RON</label>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-2">
        <?php
        echo TouchSpin::widget([
            'name' => 't6',
            'options' => ['class' => 'cos-produs-input'],

            'pluginOptions' => [
                'initval' => 1,
                'min' => 1,
                'max' => 100,
                'buttonup_class' => 'h-50 btn btn-block btn-sm btn-primary',
                'buttondown_class' => 'h-50 btn btn-sm btn-info',
                'buttonup_txt' => '+',
                'buttondown_txt' => '-',
                // 'verticalbuttons' => true
            ]
        ]);
        ?>
    </div>
    <div class="col-md-5"></div>
</div>