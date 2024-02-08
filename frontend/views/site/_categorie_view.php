<?php

use yii\helpers\Html;

$categorie = \backend\models\Categorii::findOne(['id' => $id]);
$numeId = 'v-pills-' . $id;
$ariaLabel = 'v-pills-'. $id . '-tab';

$produse = backend\models\Produse::findAll(['categorie' => $id]);
?>

<div class="tab-pane fade show active" id="<?= $numeId ?>" role="tabpanel" aria-labelledby="<?= $ariaLabel ?>">
    <div class="row">
        <?php
        foreach ($produse as $produs) {
            echo Html::beginTag('div', ['class' => 'col-md-3 text-center', 'style' => 'margin-bottom: 25px']);
            echo Html::beginTag('div', ['class' => 'menu-wrap']);
            echo Html::a('', '#', ['class' => 'menu-img img mb-3', 'style' => 'background-image: url(../themes/pizza-gh/web/images/pizza-2.jpg);']);
            echo Html::beginTag('div', ['class' => 'text']);
            echo Html::tag('h3', Html::a($produs->nume, '#'));
            echo Html::tag('p', $produs->descriere);
            echo Html::tag('p', Html::tag('span', $produs->pret_curent . ' RON', ['class' => 'price']));
            echo Html::a('Add to cart', '#', ['class' => 'btn btn-white btn-outline-white']);
            echo Html::endTag('div'); // closing div.text
            echo Html::endTag('div'); // closing div.menu-wrap
            echo Html::endTag('div'); // closing div.col-md-3.text-center
        }
        ?>
    </div>
</div>


