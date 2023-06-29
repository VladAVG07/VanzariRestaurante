<?php

use yii\widgets\ListView;
$total=0;
?>

<div id="invoice-POS">
    <center>
        <h4>S.C. RESTAURANT S.R.L.</h4>
        <h4>STR. PETROSANI, NR. 49</h4>
        <h4>ORAS CALARASI, JUD. CALARASI</h4>
        <h4>C.I.F.: RO00000000</h4>
        <h4><b>BON FISCAL</b></h4>
    </center>
    <div id="container_lista">
    <?=
    ListView::widget([
        'dataProvider' => $listDataProvider,
        'options' => [
            'tag' => 'div',
            'class' => 'list-wrapper',
            'id' => 'list-wrapper',
        ],
        'layout' => "{items}\n$total",
        'itemView' => function ($model, $key, $index, $widget) use ($total) {
            $total=$total+$model->pret;
            return $this->render('_bon_item', ['model' => $model]);

            // or just do some echo
            // return $model->title . ' posted by ' . $model->author;
        },
    ]);
    ?>
    </div>

</div>
