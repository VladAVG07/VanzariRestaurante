<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\themes\pizzagh\assets\PizzGhAsset;
use yii\helpers\Html;

//\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
PizzGhAsset::register($this);
//\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
//$this->registerCssFile('@web/assets/theme/css/theme-style.css');

//echo Yii::getAlias('@web');
$assetDir = Yii::$app->assetManager->getPublishedUrl('@webroot/css');

//$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
  <?php $this->registerCsrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <?php $this->head() ?>
</head>

<body>
  <?php $this->beginBody() ?>

  <!-- Navbar -->
  <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>
  <!-- /.content-wrapper -->
  <div class="floating-cart">
    <a class="cart-button">
      <i class="fas fa-shopping-cart" style="color:#ffffff"></i>
    </a>
  </div>
  <!-- Main Footer -->
  <?= $this->render('footer') ?>

  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>