<?php

use yii\helpers\Html;
$assetDir = Yii::$app->assetManager->getBundle('frontend\themes\pizzagh\assets\PizzGhAsset');

?>



<!-- /.navbar -->
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= Yii::$app->urlManager->createUrl(['site/pagina-home'])?>">
            <!-- <span class="flaticon-pizza-1 mr-1"></span>Pizza<br><small>Delicous</small> -->
            <img width="90px" height="56px" src="<?=$assetDir->baseUrl?>/images/logo_dio.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-home'])?>" class="nav-link">Acasă</a></li>
                <li class="nav-item"><?= Html::a('Meniu','#rest-menu',['class'=>'nav-link'])?></li>
                <li class="nav-item"><a href="#rest-program" class="nav-link">Program</a></li>
                <!-- <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li> -->
                <!-- <li class="nav-item"><a href="about.html" class="nav-link">Despre noi</a></li> -->
                <li class="nav-item"><a href="#rest-contact" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- END nav -->