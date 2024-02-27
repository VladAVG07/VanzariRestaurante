<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
use yii\helpers\Html;

$categorie = \backend\models\Categorii::findOne(['id' => $id]);
$numeId = 'v-pills-' . $id;
$ariaLabel = 'v-pills-'. $id . '-tab';

$produse = backend\models\Produse::findAll(['categorie' => $id]);
$assetDir=PizzGhAsset::register($this);
?>

<div class="tab-pane fade show active" id="<?= $numeId ?>" role="tabpanel" aria-labelledby="<?= $ariaLabel ?>">
    <div class="row">
        <?php
        foreach ($produse as $produs) {
            // echo Html::beginTag('div', ['class' => 'col-md-3 text-center', 'style' => 'margin-bottom: 25px']);
            // echo Html::beginTag('div', ['class' => 'menu-wrap']);
            // echo Html::a('', '#', ['class' => 'menu-img img mb-3', 'style' => 'background-image: url(../themes/pizza-gh/web/images/pizza-2.jpg);']);
            // echo Html::beginTag('div', ['class' => 'text']);
            // echo Html::tag('h3', Html::a($produs->nume, '#'));
            // echo Html::tag('p', $produs->descriere);
            // echo Html::tag('p', Html::tag('span', $produs->pret_curent . ' RON', ['class' => 'price']));
            // echo Html::a('Add to cart', '#', ['class' => 'btn btn-white btn-outline-white']);
            // echo Html::endTag('div'); // closing div.text
            // echo Html::endTag('div'); // closing div.menu-wrap
            // echo Html::endTag('div'); // closing div.col-md-3.text-center
            $imagine ='backend/uploads/produse/' . $produs->image_file;
            ?>
            <div class="col-md-3 text-center" style="margin-bottom:25px;">
                <div class="menu-wrap">
                    <?php if (is_null($produs->image_file)||strlen(trim($produs->image_file))===0) { ?>
                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?=$assetDir->baseUrl?>/images/no-photo.png);"></a>
                    <?php } else { ?>
                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $imagine ?>);"></a>
                    <?php } ?>
                    <div class="text">
                        <h3><a href="#"><?= $produs->nume ?></a></h3>
                        <p><?= $produs->descriere ?></p>
                        <p class="price"><span><?= $produs->pret_curent . ' RON' ?></span></p>
                        <p><a href="#" class="btn btn-white btn-outline-white btn-meniu" data-id="<?=$produs->id?>">Adauga in cos</a></p>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>


