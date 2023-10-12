<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* @var $model \backend\models\Produse */
$style = <<< CSS

.meal__wrapper{
margin: 0 2% 0 0;
padding-top:12px;
        padding-bottom:12px;
        padding-left:8px;
flex: 1;
        }
.meal-name {
    font-family: Takeaway Sans,Avant Garde,Century Gothic,Helvetica,Arial,sans-serif;
    font-weight: 600;
    font-size: 18px;
    line-height: 1.22;
    color: #0a3847;
}
.meal__description-additional-info {
    font-family: Takeaway Sans,Avant Garde,Century Gothic,Helvetica,Arial,sans-serif;
    font-weight: 400;
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    margin: 0 0 4px;
}
.meal__description-choose-from {
    font-family: Takeaway Sans,Avant Garde,Century Gothic,Helvetica,Arial,sans-serif;
    font-style: italic;
    font-size: 12px;
    color: #666;
    line-height: 1.5;
    margin: 0 0 4px;
}
.meal__wrapper .meal__price {
    font-family: Takeaway Sans,Avant Garde,Century Gothic,Helvetica,Arial,sans-serif;
    font-weight: 600;
    font-size: 18px;
    line-height: 1.22;
    letter-spacing: -.5px;
    color: #ff8000;
    margin: auto 0 0;
}
.meal-container {
    border: 1px solid #ebebeb;
    border-radius: 2px;
    margin: 8px 0;
    position: relative;
}
.meal {
    display: flex;
    flex-wrap: wrap;
    align-items: stretch;
}
.right-plus{
    position:static;
}
.left-corner{
        font-family: inherit;
    font-size: 22px;
        font-weight:bold;
        color:blue;
    vertical-align: baseline;
    border: 0;
    outline: 0;
          border-left:1px solid #cecece;
        border-bottom:1px solid #cecece;
      background-color:white;
    cursor: pointer;
        width:40px;
        height:40px;
}
CSS;
$this->registerCss($style);

if ($model->stocabil){
    $query= \backend\models\Stocuri::find()
            ->select(['stocuri.id','produs',new \yii\db\Expression('SUM(cantitate_ramasa) AS cantitate_ramasa')])
            ->innerJoin('produse p', 'stocuri.produs = p.id')
            ->where(['p.id' => $model->id]);
    $stocRamas = $query->one()->cantitate_ramasa; 
}

?>
<div class="meal-container js-meal-container js-meal-search-popularOPON0O17Q" id="popularOPON0O17Q">
    <div tabindex="0" role="button" class="meal meal__top-button js-meal-item" itemscope="" itemtype="http://schema.org/Product">
        <div class="meal__wrapper js-has-sidedishes">
            <div class="meal-json"><?= \yii\helpers\Json::encode($model->toArray()) ?></div>
            <div class="meal__description-texts js-meal-description-text">
                <span class="meal-name" itemprop="name">
                    <?php if ($model->stocabil){ ?>
                        <?php if (is_null($stocRamas) || $stocRamas == 0){ ?>
                            <span data-product-name="Pizza Casei Camizo"><?= $model->nume ?>   <span style="color:#ff0000;">Stoc: 0</span> </span>
                        <?php } else if ($stocRamas < $model->alerta_stoc){ ?> 
                            <span data-product-name="Pizza Casei Camizo"><?= $model->nume ?>   <span style="color:#ff9500;">Stoc: <?= $stocRamas ?></span> </span>
                        <?php } else { ?> 
                            <span data-product-name="Pizza Casei Camizo"><?= $model->nume ?>   <span style="color:green;">Stoc: <?= $stocRamas ?></span> </span>
                        <?php } ?>
                    <?php }else{ ?>
                        <span data-product-name="Pizza Casei Camizo"><?= $model->nume ?></span>
                    <?php } ?>
                        
                     
                </span>
                <div class="meal__description-additional-info" itemprop="description"><?= $model->descriere ?></div>
                <div class="meal__description-choose-from">Alege: Usturoi, Branza de burduf, Gorgonzola, Mozzarella, Parmezan, Pecorinno, Ceapa și mai multe.</div>

                <div itemprop="price" class="meal__price"><?= Yii::$app->formatter->asCurrency($model->pret_curent)?></div>
                <div class="sizeattributecontainer" id="sizeattributecontainerOPON0O17Q"></div>                
            </div>
        </div>
        <div class="right-plus"> <button data-id="<?=$model->id?>" class="left-corner">+</button></div>
    </div>
    <div class="meal meal__bottom-wrapper">
        <div class="sidedish-pop-in sidedish-container" id="sidedishesproductformpopularOPON0O17Q"></div>    </div>
</div>