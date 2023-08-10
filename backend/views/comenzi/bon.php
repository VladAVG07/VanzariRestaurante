<?php

use yii\widgets\ListView;

$total = 0;
$produse = $listDataProvider->getModels();
foreach ($produse as $produs) {
    $total += $produs->pret;
}
$pretFaraTva = $total / 1.09;
$tva = $total - $pretFaraTva;
?>

<div id="invoice-POS">
    <center>
        <h5 style="margin:0;padding:0">S.C. RESTAURANT S.R.L.</h5>
        <h5 style="margin:0;padding:0">STR. PETROSANI, NR. 49</h5>
        <h5 style="margin:0;padding:0">ORAS CALARASI, JUD. CALARASI</h5>
        <h5 style="margin:0;padding:0">C.I.F.: RO00000000</h5>
    </center>
    <br>
    <div id="container_lista">
        <?=
        ListView::widget([
            'dataProvider' => $listDataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'list-wrapper',
                'id' => 'list-wrapper',
            ],
            'layout' => "{items}",
            'itemView' => function ($model, $key, $index, $widget) use ($total) {
                $total = $total + $model->pret;
                return $this->render('_bon_item', ['model' => $model]);

                // or just do some echo
                // return $model->title . ' posted by ' . $model->author;
            },
        ]);
        ?>
    </div>
    <h5 style="margin-bottom: 0">----------------------------------------------</h5>
    <div class="row">
        <div class="col-md-6" style="font-size: 21px"><b>TOTAL</b></div>
        <div class="col-md-6"><b> 
                <span class="float-right"  style="font-size: 21px"> <?= number_format((float)$total, 2, '.', '') ?> </span></b>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6" style="font-size: 18px">TVA</div>
        <div class="col-md-6"> 
                <span class="float-right"  style="font-size: 18px"> <?= number_format((float)$tva, 2, '.', '') ?> </span>
        </div>
    </div>
    <div style="font-size: 18px; margin-top: 15px;">Numar bon: 0000</div>
    <div class="row">
        <div class="col-md-6">
            <span style="font-size: 18px">Data: <?= date('d.m.Y', time()) ?></span>
        </div>
        <div class="col-md-6">
            <span class="float-right" style="font-size: 18px">Ora: <?= date('H:i:s', time())?></span>
        </div>
    </div>
    <div style="font-size: 18px;">Operator: Emanuel</div>
    <br>
    <center><h6 style="margin-bottom: 0"><b>BON FISCAL</b></h6></center>
    <center><h6 style="margin-bottom: 0">RL CL0000000000</h6></center>
</div>
