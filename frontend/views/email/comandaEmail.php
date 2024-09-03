<!-- views/email/welcomeEmail.php -->
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */

/** @var \frontend\models\FormularComanda $model */
/** @var \frontend\models\Basket $basket */

$this->title = 'Welcome Email';
$assetDir = Yii::$app->assetManager->getBundle('frontend\themes\pizzagh\assets\PizzGhAsset');

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <style>
        .content {
            background-color: white;
            width: 560px;
            /* margin: auto;
            /* Center the content horizontally */
        }

        .header {
            background-color: #000;
            height: 80px;
            border-radius: 10px;
            vertical-align: middle;
            text-align: center;
        }

        .htd img {
            width: 90px;
            height: 56px;
            vertical-align: middle;
            margin-right: 10px;
            /* Add some space between logo and text */
        }

        .header h4 {
            color: white;
            margin: 0;
            display: inline-block;
            vertical-align: middle;
        }

        .mesaj {
            text-align: center;
            padding: 10px 20px;
            /* Add some padding */
        }

        .bold_red {
            color: red;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 10px 0;
            /* Add some padding */
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* Add some space between message and table */
        }

        .order-table th,
        .order-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .order-table th {
            background-color: #f2f2f2;
            /* Gray background for header cells */
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            /* Add some space between order table and product table */
        }

        .product-table th,
        .product-table td {
            border: 1px solid #000;
            padding: 8px;
        }

        .product-table th {
            background-color: #f2f2f2;
            /* Gray background for header cells */
        }
        .product-table .tdcenter{
            text-align: center;
        }

        .info {
            background-color: black;
            border: 1px solid #000;
            border-radius: 10px;
            color: white;
            display: flex;

            padding: 20px;
            margin-top: 20px;
            /* Add some space between product table and info div */
        }

        .col-md-3 {
            width: 32%;
            margin-right: 10px;
        }

        .icon-red {
            color: red;
            fill: currentColor;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="header">
            <table style="width:100%; height:100%;padding-left:10px; padding-right:10px">
                <tr>
                    <td colspan="2" style="text-align: left;">
                        <img width="90px" height="56px" src="https://diobistro.ro/<?= $assetDir->baseUrl ?>/images/logo_dio.png" alt="Dio Bistro Logo" />
                    </td>
                    <td colspan="2" style="text-align: right;">
                        <h4 style="color: #fac564;">Dio Bistro</h4>
                    </td>
                </tr>
            </table>
        </div>
        <table cellpadding="0" cellspacing="0" border="0">

            <tr>
                <td class="mesaj">
                    <span class="bold_red"> Acesta este un email generat automat. Vă rugăm nu răspundeți.</span>
                    <br>
                    <span>Dacă doriți să vă modificați sau să vă anulați comanda, vă rugăm să ne contactați telefonic. +40722 885 551</span>
                    <br>
                    <span>Comanda <?=sprintf('#%s',str_pad($model->numarComanda,9,'0',STR_PAD_LEFT))?> a fost primită! Mulțumim</span>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="order-table">
                        <tr>
                            <th>Nume:</th>
                            <td><?= $model->numeComplet ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?= $model->email ?></td>
                        </tr>
                        <tr>
                            <th>Telefon:</th>
                            <td><?= $model->telefon ?></td>
                        </tr>
                        <tr>
                            <th>Livrare la:</th>
                            <td><?= $model->adresaCompleta ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Produs</th>
                                <!-- <th>Categorie</th> -->
                                <th>Cantitate</th>
                                <th>Pret</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            /** @var \frontend\models\BasketItem $basketItem */
                            $total=0.00;
                            foreach ($basket->basketItems as $basketItem) {
                                $total+=($basketItem->pret*$basketItem->cantitate);
                            ?>
                                <tr>
                                    <td><?=$basketItem->denumire?></td>
                                    <!-- <td class="tdcenter">-</td> -->
                                    <td class="tdcenter"><?=$basketItem->cantitate?></td>
                                    <td class="tdcenter"><?=number_format($basketItem->pret,2)?></td>
                                    <td class="tdcenter"><?=number_format($basketItem->pret*$basketItem->cantitate,2)?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            <!-- Product rows go here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total</th>
                                <td class="tdcenter"><?=number_format($total,2)?></td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </table>
        <div class="info">
            <div class="col-md-3">
                <p style="margin-top:0px !important; margin-bottom:0px !important;"><img width="20px" height="20px" src="https://diobistro.ro/<?= $assetDir->baseUrl ?>/images/phone.png" alt="Phone icon" />
                    +40722 885 551</p>
                <br />
                <span>DIO Bistro este locul unde tu și prietenii tăi o să vă simțiți minunat!</span>

            </div>
            <div class="col-md-3">
                <p style="margin-top:0px !important; margin-bottom:0px !important;">
                    <img width="20px" height="20px" src="https://diobistro.ro/<?= $assetDir->baseUrl ?>/images/location.png" alt="Location icon" />
                    Prel. București nr. 123
                </p>
                <br />
                <span>Călărași, România</span>
            </div>
            <div class="col-md-3">
                <p style="margin-top:0px !important; margin-bottom:0px !important;">
                    <img width="20px" height="20px" src="https://diobistro.ro/<?= $assetDir->baseUrl ?>/images/watch.png" alt="Watch icon" />
                    Livrari
                </p>
                <br />
                <span>
                    L-V 8-22
                    <br/>
                    S-D 10-22
                </span>
            </div>
        </div>
        <div class="footer">
            <p>&copy; 2024 Dio Bistro. Toate drepturile rezervate.</p>
        </div>
    </div>
</body>

</html>