<?php

use yii\helpers\Html;
use frontend\themes\pizzagh\assets\PizzGhAsset;


$assetDir = Yii::$app->assetManager->getBundle('frontend\themes\pizzagh\assets\PizzGhAsset');


$produse = \backend\models\Produse::find()->where(['categorie' => $idCategorie])->orderBy(['ordine'=>SORT_ASC,'nume'=>SORT_ASC])->all();
$nrProduse = sizeof($produse);

$produse1 = array_slice($produse, 0, round($nrProduse / 2));
$produse2 = array_slice($produse, round($nrProduse / 2));
?>
<?php
foreach ($produse as $produs) {
?>
    <div class="col-md-6">

        <?php
        
        $imagine = 'backend/uploads/produse/' . $produs->image_file;
        ?>
        <div class="pricing-entry d-flex ftco-animate" data-id=<?= $produs->id ?>>
            <!-- <?php if (is_null($produs->image_file) || strlen(trim($produs->image_file)) === 0) { ?>
                <div class="img" style="background-image: url(<?= $assetDir->baseUrl ?>/images/no-photo.png);"></div>
            <?php } else { ?>
                <div class="img" style="background-image: url(<?= $imagine ?>);"></div>
            <?php } ?> -->
            <div class="desc w-100 pr-2 pl-1">
                <div class="d-flex text align-items-center">
                    <h3><span><?= sprintf('%s %s<br />%s',$produs->nume,$produs->picant?'<i class="fas fa-pepper-hot" data-toggle="tooltip" data-placement="top" title="Picant" style="color: #ff0000;"></i>':'',$produs->produsDetaliiDescriere) ?></span></h3>
                    <span class="price"><?= $produs->pretMeniu ?></span>
                </div>
                <div class="row">
                    <div class="col-md-7" <p><?= $produs->descriere ?></p>
                    </div>
                    <div class="col-md-5 text-right">
                        <!-- <a href="#" class="btn btn-meniu btn-meniu-stil">
                        <i class="fas fa-shopping-basket" style="color: #ffffff;"></i>
                    </a> -->
                        <a href="#" class="btn btn-white btn-outline-white btn-meniu" data-id="<?= $produs->id ?>">Adaugă în coș</a>

                    </div>
                </div>
            </div>
        </div>

    </div>
<?php
}
?>