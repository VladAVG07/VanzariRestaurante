<?php

use yii\helpers\Html;

$categorie = \backend\models\Categorii::findOne(['id' => $id]);
$numeId = 'v-pills-' . $id;
$ariaLabel = 'v-pills-' . $id . '-tab';

$produse = backend\models\Produse::findAll(['categorie' => $id]);
?>

<div class="tab-pane fade show active" id="<?= $numeId ?>" role="tabpanel" aria-labelledby="<?= $ariaLabel ?>">
    <div class="row">
        <?php
        foreach ($produse as $produs) {
            $imagine = Yii::getAlias('@backendPictures') . '/' . $produs->image_file;
            ?>
            <div class="col-md-3 text-center" style="margin-bottom:25px;">
                <div class="menu-wrap">
                    <?php if (is_null($produs->image_file)) { ?>
                        <a href="#" class="menu-img img mb-4" style="background-image: url(../themes/pizza-gh/web/images/pizza-2.jpg);"></a>
                    <?php } else { ?>
                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $imagine ?>);"></a>
                    <?php } ?>
                    <div class="text">
                        <h3><a href="#"><?= $produs->nume ?></a></h3>
                        <p><?= $produs->descriere ?></p>
                        <p class="price"><span><?= $produs->pret_curent . ' RON' ?></span></p>
                        <p><a href="#" class="btn btn-white btn-outline-white btn-meniu">Adauga in cos</a></p>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>


