<?php

use yii\helpers\Html;

$produse = \backend\models\Produse::findAll(['categorie' => $idCategorie]);
$nrProduse = sizeof($produse);

$produse1 = array_slice($produse, 0, round($nrProduse / 2));
$produse2 = array_slice($produse, round($nrProduse / 2));
?>

<div class="col-md-6">
    <?php
    foreach ($produse1 as $produs) {
        $imagine = Yii::getAlias('@backendPictures') . '/' . $produs->image_file;
        ?>    
        <div class="pricing-entry d-flex ftco-animate" data-id=<?=$produs->id?>>
            <?php if (is_null($produs->image_file)) { ?>
                <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-1.jpg);"></div>
            <?php } else { ?>
                <div class="img" style="background-image: url(<?= $imagine ?>);"></div>
            <?php } ?>
            <div class="desc pl-3">
                <div class="d-flex text align-items-center">
                    <h3><span><?= $produs->nume ?></span></h3>
                    <span class="price"><?= $produs->pret_curent . ' RON' ?></span>
                </div>
                <div class="row">
                    <div class="col-md-10"
                         <p><?= $produs->descriere ?></p>
                    </div>
                    <div class="col-md-2">
                        <i class="btn btn-meniu btn-meniu-stil fas fa-shopping-basket" style="color: #ffffff;"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
<div class="col-md-6">
    <?php
    foreach ($produse2 as $produs) {
        $imagine = Yii::getAlias('@backendPictures') . '/' . $produs->image_file;
        ?>    
        <div class="pricing-entry d-flex ftco-animate" data-id=<?=$produs->id?>>
            <?php
            if (is_null($produs->image_file)) {
                ?>
                <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-1.jpg);"></div>
                <?php
            } else {
                ?>
                <div class="img" style="background-image: url(<?= $imagine ?>);"></div>
                <?php
            }
            ?>
            <div class="desc pl-3">
                <div class="d-flex text align-items-center">
                    <h3><span><?= $produs->nume ?></span></h3>
                    <span class="price"><?= $produs->pret_curent . ' RON' ?></span>
                </div>
                <div class="row">
                    <div class="col-md-10"
                         <p><?= $produs->descriere ?></p>
                    </div>
                    <div class="col-md-2">
                        <i class="btn btn-meniu btn-meniu-stil fas fa-shopping-basket" style="color: #ffffff;"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>



