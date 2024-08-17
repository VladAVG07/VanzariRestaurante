<?php
$comanda = \backend\models\Comenzi::findOne(['id' => $id]);
$comenziLinii = backend\models\ComenziLinii::findAll(['comanda' => $id]);
$modPlata = backend\models\ModuriPlata::findOne(['id' => $comanda->mod_plata]);


$date = new DateTime($comanda->data_ora_creare);
$dataComanda = $date->format('d.m.Y');
$oraComanda = $date->format('H:i');
?>


<div class="bon-layout" id="bonProduse">

    <div class="row" style="font-size:1rem">
        <div class="col-md-8"><?= $dataComanda ?></div>
        <div class="col-md-4">
            <span class="float-right"><?= $oraComanda ?></span>
        </div>
    </div>

    <center><p style="font-size:1.3rem">Comanda <?= $comanda->numar_comanda ?></p></center>

    <hr style="border: 3px dotted #000000;border-style: none none dotted; margin-top: 4px;margin-bottom: 4px">
    <div style="text-align: center;">
        <span style="font-size:1.1rem">Telefon</span>
        <h2><?= $comanda->numar_telefon ?></h2>
    </div>
    <hr style="border: 3px dotted #000000;border-style: none none dotted; margin-top: 4px;margin-bottom: 4px">
    <div style="text-align:center">
        <p style="font-size:1.1rem"><?= $comanda->adresa ?></p>
    </div>
    <hr style="border: 3px dotted #000000;border-style: none none dotted; margin-top: 4px;margin-bottom: 4px">
    <?php
    foreach ($comenziLinii as $comandaLinie) {
        $produs = \backend\models\Produse::findOne(['id' => $comandaLinie->produs]);

        if (!is_null($comandaLinie->produs_detaliu)) {
            $detaliu = backend\models\ProduseDetalii::find()->where(['id' => $comandaLinie->produs_detaliu])->one();
        } else {
            $detaliu = new backend\models\ProduseDetalii();
            $detaliu->pret = $produs->pret_curent;
        }

//        if (empty($detaliu->descriere))
//            $linie = '<span><b>'.$comandaLinie->cantitate.'</b>' . ' x ' .$produs->nume.'</span>';
//        else
//            $linie = '<span><b>'.$comandaLinie->cantitate . '</b> x ' .$produs->nume . ' - ' . $detaliu->descriere.'</span>';
        if (empty($detaliu->descriere))
            $linie = '[&nbsp;&nbsp;&nbsp;] ' . $comandaLinie->cantitate . 'x ' . $produs->nume;
        else
            $linie = '[&nbsp;&nbsp;&nbsp;] ' . $comandaLinie->cantitate . 'x ' . $produs->nume . ' - ' . $detaliu->descriere;
        if ($produs->picant)
            $linie = $linie . '<span><b> (PICANT)</b></span>';
        ?>
        <div class="row" style="margin-top:1rem">
            <span style="font-size: 1rem"><?= $linie ?></span>
            <span style="text-align: right;font-size: 1.1rem;margin-top:-0.4rem"><?= $detaliu->pret ?> RON</span>
        </div>

        <?php
    }
    ?>
    <div style="text-align: center;">
        <h3>Total: <?= $comanda->pret ?> RON</h3>
    </div>
    <div>
        <hr style="border: 3px dotted #000000;border-style: none none dotted; margin-top: 4px;margin-bottom: 4px">
        <span style="display: block;text-align: center;text-transform: uppercase;font-size: 1.5rem"><?= $modPlata ? $modPlata->nume : 'NULL' ?></span>
        <hr style="border: 3px dotted #000000;border-style: none none dotted; margin-top: 4px;margin-bottom: 4px">
    </div>
    <div>
        <b>Mentiunile clientului:</b> <?= $comanda->mentiuni ?>
    </div>
</div>