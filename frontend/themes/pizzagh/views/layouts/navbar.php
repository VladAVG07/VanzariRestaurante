<?php

use yii\helpers\Html;
?>



<!-- /.navbar -->
<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?= Yii::$app->urlManager->createUrl(['site/pagina-home'])?>"><span class="flaticon-pizza-1 mr-1"></span>Pizza<br><small>Delicous</small></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-home'])?>" class="nav-link">Acasă</a></li>
                <li class="nav-item"><a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-meniu'])?>" class="nav-link">Meniu</a></li>
                <li class="nav-item"><a href="services.html" class="nav-link">Services</a></li>
                <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="about.html" class="nav-link">Despre noi</a></li>
                <li class="nav-item"><a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-contact'])?>" class="nav-link">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- END nav -->