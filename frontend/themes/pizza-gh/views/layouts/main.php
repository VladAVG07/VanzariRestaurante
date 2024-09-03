<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

//\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
//\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
//$this->registerCssFile('@web/assets/theme/css/theme-style.css');

echo Yii::getAlias('@web');
$assetDir = Yii::$app->assetManager->getPublishedUrl('@frontend/themes/pizza-gh/web');

//$publishedRes = Yii::$app->assetManager->publish('@vendor/hail812/yii2-adminlte3/src/web/js');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">

    <link rel="stylesheet" href="themes/pizza-gh/web/css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="themes/pizza-gh/web/css/animate.css">
    
    <link rel="stylesheet" href="frontend/themes/pizza-gh/web/css/owl.carousel.min.css">
    <link rel="stylesheet" href="themes/pizza-gh/web/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="themes/pizza-gh/web/css/magnific-popup.css">

    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/aos.css">

    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/ionicons.min.css">

    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/flaticon.css">
    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/icomoon.css">
    <link rel="stylesheet" href="/frontend/themes/pizza-gh/web/css/style.css">
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

    <!-- Main Footer -->
    <?= $this->render('footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
