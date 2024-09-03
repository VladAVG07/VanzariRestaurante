<!-- views/layouts/emailLayout.php -->
<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
   
</head>

<body>
    <?= $content ?>
</body>

</html>