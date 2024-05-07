<?php
$comanda = \backend\models\Comenzi::findOne(['id' => $id]);
$comenziLinii = backend\models\ComenziLinii::findAll(['comanda' => $id]);
?>


<div id="invoice-POS">
    <center><h4>Comanda <?= $comanda->numar_comanda ?></h4></center>
    <h5 style="margin-bottom: 0">----------------------------------------------</h5>
    <?php
    foreach ($comenziLinii as $comandaLinie) {
        $produs = \backend\models\Produse::findOne(['id'=>$comandaLinie->produs]);
        $detaliu = backend\models\ProduseDetalii::findOne(['id'=>$comandaLinie->produs_detaliu]);
        if (empty($detaliu->descriere))
            $linie = $comandaLinie->cantitate . ' x ' .$produs->nume;
        else
            $linie = $comandaLinie->cantitate . ' x ' .$produs->nume . ' - ' . $detaliu->descriere;
        
    ?>
    <div class="row" style="margin-bottom:10px">
        <?= $linie ?>
        <?php if ($produs->picant){ ?>
        (PICANT)
        <?php } ?>
    </div>
    <?php
    }
    ?>
    <b>Mentiuni:</b> <?= $comanda->mentiuni ?>
</div>