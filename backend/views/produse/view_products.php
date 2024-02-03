<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use \yii\bootstrap5\Modal;
use backend\assets\AppComenziAsset;
AppComenziAsset::register($this);


/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $form \kartik\form\ActiveForm */

// Include KioskBoard JavaScript and CSS assets
//$this->registerJsFile('../../vendor/npm-asset/kioskboard/dist/kioskboard-2.3.0.min.js', ['position' => \yii\web\View::POS_HEAD]);
//$this->registerCssFile('../../vendor/npm-asset/kioskboard/dist/kioskboard-2.3.0.min.css');
//$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/css/index.css', ['position' => \yii\web\View::POS_HEAD]);
//$this->registerCssFile('../index.css');


$this->params['breadcrumbs'][] = ['label' => 'Produses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$searchId = Html::getInputId($model, 'nume');
$urlSearch = '';

$urlProduse = \yii\helpers\Url::toRoute('produse/proceseaza-comanda');
$urlComenzi = \yii\helpers\Url::toRoute('produse/afisare-istoric');
$urlSchimba = yii\helpers\Url::toRoute('produse/schimba-categoria');
$urlCreazaComanda = \yii\helpers\Url::toRoute('comenzi/create');
$urlVerificaStoc = \yii\helpers\Url::toRoute('produse/verifica-stoc');
$urlIncarcareSesiune = yii\helpers\Url::toRoute('produse/incarcare-sesiune');
$authKey = Yii::$app->user->identity->auth_key;
$verificaStoc = 'http://localhost/VanzariRestaurant/api/web/v1/produse/verifica-stoc';
$comandaSesiune = Yii::$app->urlManager->createUrl('produse/comanda-sesiune');
$produsSesiune = 'http://localhost/VanzariRestaurant/api/web/v1/produse/produs-sesiune';
$idUser = Yii::$app->user->id;

$setariLivrare = backend\models\SetariLivrare::find()
                ->innerJoin('restaurante r', 'r.id = setari_livrare.restaurant')
                ->innerJoin('restaurante_user ru', 'ru.restaurant = r.id')
                ->innerJoin('user u', 'ru.user = u.id')
                ->where(['u.id' => $idUser])->orderBy(['id' => SORT_DESC])->one();
$pretLivrare = $setariLivrare->produs0->pret_curent;
$comandaMinima = $setariLivrare->comanda_minima;
$livrare = 0;

$sesiune = backend\models\Sesiuni::findOne(['user' => \Yii::$app->user->id, 'data_ora_sfarsit' => NULL]);
if (!is_null($sesiune)) {
    $sesiuniProduse = \backend\models\SesiuniProduse::find()->where(['sesiune' => $sesiune])->all();
    $comenziLinii = [];
    foreach ($sesiuniProduse as $sesiuneProdus) {
        if ($sesiuneProdus->cantitate > 0) {
            $comandaLine = new \backend\models\ComenziLinii();
            $comandaLine->produs = $sesiuneProdus->produs;
            $comandaLine->cantitate = $sesiuneProdus->cantitate;
            array_push($comenziLinii, $comandaLine);
        }
    }
    $dataProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $comenziLinii,
    ]);
}


$formatJsH = <<< SCRIPT
      var inputElement = $("#$searchId");
      var modal = $("#myModal");
      inputElement.on("click", function() {
        modal.css("display", "block");
        console.log('salut');
      });
      $(".close-tst").on("click", function() {
        modal.css("display", "none");
      });
    $('.responsive').slick({
        dots: false,
        arrows:false,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        appendDots: $('.responsive'), // Append dots to the carousel container
    customPaging: function(slider, i) {
        return ''; // Return an empty string to remove the page number
      },
        responsive: [
        {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: true
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});   
      
//     $(document).on('click', function(event) {
//        if(event.target===$("#produsesearch-nume")[0])
//            return;
//        console.log(event.target);
//         var modal = $("#myModal");
//         modal.css("display", "none");
//    });
SCRIPT;
$this->registerJs($formatJsH, yii\web\View::POS_END);

$formatJs = <<< SCRIPT
   let linii=[];
        
        incarcaSesiune('$urlIncarcareSesiune',$comandaMinima,$pretLivrare);
        
        loadContinutSubCategorii('$urlProduse');

        cartDeleteButton('$authKey','$produsSesiune','$idUser',$comandaMinima,$pretLivrare);
        
        cartRemoveButton('$authKey','$produsSesiune','$idUser',$comandaMinima,$pretLivrare);
   
        cartAddButton('$authKey','$produsSesiune','$idUser',$comandaMinima,$pretLivrare);
        
        subcategoriiAddButton('$authKey','$produsSesiune','$idUser',$comandaMinima,$pretLivrare);
        
    const Item=({id,cantitate,denumire,simbol,pret})=>`
        <div data-key='\${id}'> 
        <div class="cart-single-meal">
            <div class="cart-row" data-restaurant="1" data-id='\${id}' data-cantitate='\${cantitate}' data-pret='\${pret}'>
                <div class="meal-json"></div>
                <span class="cart-meal-amount"><span class="amount">\${cantitate}</span>x</span>
                <span class="cart-meal-name">\${denumire}</span>
                <div class="cart-meal-edit-buttons">
                    <button class="remove edit-delete" onclick="">-</button>
                    <button class="add edit-delete">+</button>
                </div>
                <span class="cart-meal-price"><span class="price">\${pret}</span> Lei</span>
                <button class="cart-delete-button"><i class="fas fa-trash-alt" style="color: #ff0000;"></i></button>
            </div>
        </div>
        </div>`;
    let timer;
    let timeout=1000;    
    searchBar('#$searchId');     
    $('.taba').eq(1).click();   
    buttonConfirm('$urlCreazaComanda');
    slickSlide('$urlSchimba');
     $(".kioskboard-row").on('click','.kioskboard-key',function(){
        alert('test');
    // var keyValue=$(this).attr("data-value");
    // alert(keyValue);
   });   
    const socket = io('http://localhost:8000', { transports: ['websocket', 'polling'] });
    socketIo('$urlComenzi');
SCRIPT;

// Register the formatting script
$this->registerJs($formatJs, yii\web\View::POS_END);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/simple-keyboard@latest/build/index.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.socket.io/4.1.2/socket.io.min.js');
//$this->registerJsFile('../index1.js', ['position' => \yii\web\View::POS_END]);

Modal::begin([
    'title' => '<h4>Confirmare comanda</h4>',
    'id' => 'confirmation-modal',
]);
?>

<h5>Adresa livrare</h5>
<textarea id="text-area-adresa" class="form-control" rows="3"></textarea>
<h5>Mentiuni</h5>
<textarea id="text-area-mentiuni" class="form-control" rows="3"></textarea>
<h5>Numar telefon</h5>
<input id="text-nr-telefon" class="form-control">
<br>
<span class="float-right">
    <a class="btn btn-app bg-success" id="btn-confirma" style="width:120px;height:50px;text-align: center; align-items: center; display: flex; justify-content: center;">
        <span style="font-size:20px;">Confirma</span>
    </a>
</span>
<?php
Modal::end();
?>


<div class="row">
    <!--<div class="col-md-12">-->
    <div class="col-md-7">

        <div class="box box-danger card" style="padding:0.1rem;">
            <div id="istoric" class="box-header with-border">
                <h4 class="box-title"><center>Istoric comenzi</center></h4>
            </div>
        </div>

        <div class="produse-view box box-primary">
            <?php
            $form = ActiveForm::begin([
                        'action' => ['proceseaza-comanda'],
                        'method' => 'get',
                        'id' => 'search-form',
                        'options' => [
                            'data-pjax' => 1,
                        ],
            ]);
            //   echo Html::tag('span', 'Test', ['class' => 'test']);
            Modal::begin([
                'title' => 'Sumar comanda',
                'id' => 'modal-sumar-comanda',
                'size' => 'modal-lrg',
                'closeButton' => ['id' => 'close-button'],
                'clientOptions' => [
                    'backdrop' => 'static',
                    'keyboard' => false,
                ]
            ]);

//                echo Html::beginTag('div');
//                echo Html::img(sprintf('%s/images/loading.gif', Yii::getAlias('@web')), ['style' => 'padding: 20px;margin:0 auto;', 'class' => 'loading-spinner']);
            echo Html::tag('div', '', ['id' => 'modal-sumar-comanda-content', 'class' => 'modal-body-content']);
//                echo Html::endTag('div');
            Modal::end();
            ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <!--<input class="js-virtual-keyboard" data-kioskboard-type="keyboard" data-kioskboard-placement="bottom" data-kioskboard-specialcharacters="false" placeholder="Your Name" />-->

                        <?php
                        echo $form->field($model, 'nume', [
                            'addon' => ['prepend' => ['content' => '<i class="fa fa-search"></i>']]
                        ])->textInput(['maxlength' => true, 'class' => 'input',
                            //'id' => 'openModalButton',
                            'data-kioskboard-type' => 'keyboard',
                            'data-kioskboard-placement' => 'bottom',
                            'data-kioskboard-placement' => false,
                            'placeholder' => 'Produsul cautat...'])
                        ?>
                    </div>
                </div>




            </div>
            <div class="row">

                <div class="col-sm-12">
                    <div class="responsive">
                        <?php
                        $categorii = \backend\models\Categorii::find()
                                ->innerJoin('produse p', 'p.categorie = categorii.id')
                                //->innerJoin('categorii c', 'c.parinte = categorii.id')
                                ->innerJoin('restaurante_categorii rc', 'rc.categorie=categorii.id')
                                ->innerJoin('restaurante r', 'rc.restaurant=r.id')
                                ->innerJoin('restaurante_user ru', 'ru.restaurant=r.id')
                                ->innerJoin('user u', 'ru.user=u.id')
                                ->where(['u.id' => \Yii::$app->user->id])->andWhere(['<>', 'categorii.nume', 'Servicii'])->andWhere(['categorii.parinte' => null])
                                ->all();
                        $categorii1 = \backend\models\Categorii::find()
                                //  ->innerJoin('produse p', 'p.categorie = categorii.id')
                                ->innerJoin('categorii c', 'c.parinte = categorii.id')
                                ->innerJoin('restaurante_categorii rc', 'rc.categorie=categorii.id')
                                ->innerJoin('restaurante r', 'rc.restaurant=r.id')
                                ->innerJoin('restaurante_user ru', 'ru.restaurant=r.id')
                                ->innerJoin('user u', 'ru.user=u.id')
                                ->where(['u.id' => \Yii::$app->user->id])->andWhere(['<>', 'categorii.nume', 'Servicii'])->andWhere(['categorii.parinte' => null])
                                ->all();
                        $categorii = array_merge($categorii, $categorii1);
                        foreach ($categorii as $categorie) {
                            //    echo $categorie->nume;
                            echo Html::a($categorie->nume, sprintf('#%s', yii\helpers\Inflector::slug($categorie->nume)), ['data-id' => $categorie->id, 'class' => 'btn btn-app', 'style' => 'width:200px;height:100px;
    line-height:70px;display: inline-block;
    margin-right: 10px;']);
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div id="myModal" class="modal-tst">
                <div class="modal-content-tst">
                    <div class="modal-header-tst">
                        <span class="modal-title-tst">Tastatura virtuala</span>
                        <span class="close-tst">&times;</span>
                    </div>
                    <div class="keyboardContainer">
                        <div class="simple-keyboard-main"></div>
                        <div class="numPad">
                            <div class="simple-keyboard-numpad"></div>
                            <div class="simple-keyboard-numpadEnd"></div>
                        </div>
                    </div>

                </div>
            </div>
            <?php
            $form->end();
            ?>
            <div class="row" id="subcategorii_content">
                <?php
                    echo $this->render('_subcategorii_view',['id'=>5]);
                ?>
                
            </div>
        </div>
    </div>
    <div class="col-md-5">

        <div class="cos">

            <div class="box box-primary card" style="padding:1.25rem;">
                <div class="box-header text-center with-border">
                    <h3 class="box-title">Cos</h3>


                    <!--              <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>-->
                </div>
                <div class="loading-overlay" id="loadingOverlay">
                    <div class="loading-spinner"></div>
                </div>
                <div class="test">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <?php
//                    if (!is_null($sesiune)) {
//                        echo $this->render('_list_view_cos', [
//                            'comenziLiniiDataProvider' => $dataProvider,
//                        ]);
//                    }
                        ?>
                        <?php
                        \yii\widgets\Pjax::begin(['id' => 'cos-list', 'timeout' => 30000, 'clientOptions' => ['container' => 'pjax-container']]);
                        echo yii\widgets\ListView::widget([
                            'dataProvider' => $dataProviderCos,
                            'options' => ['data-pjax' => true],
                            //   'layout' => '{items}{summary}',
                            'itemView' => '_linie_comanda',
                            'emptyText' => '<center><i class="fas fa-shopping-basket" style="color: #FF0000;font-size:150px;"></i></center>',

                        ]);
                        yii\widgets\Pjax::end();
                        ?>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <div id="sum" class="cart-sum js-basket-sum" style="display:none;margin-bottom: 20px;"><div class="cart-row js-subtotal-row" style="display: flex;">
                                <span class="cart-sum-name grey">Sub-total</span>
                                <span class="cart-sum-price-sub grey">32,00 lei</span>
                            </div><div class="cart-row js-delivery-costs-row" style="display: flex;">
                                <span class="cart-sum-name grey"><?= $setariLivrare->produs0->nume ?></span>
                                <span class="cart-sum-price-livrare grey"><?= $setariLivrare->produs0->pret_curent ?> lei</span>
                            </div><div class="cart-row row-sum js-total-costs-row" style="display: flex;font-weight: bold">
                                <span class="cart-sum-name">Total</span>
                                <span class="cart-sum-price">36,00 lei</span>
                            </div></div>
                        <?= Html::button('Comanda', ['id' => 'btn-comanda', 'class' => 'btn btn-block btn-default btn-lg disabled']) ?>

                    </div>
                </div>
                <!-- /.box-footer -->
            </div>
        </div>
    </div>
    <!--</div>-->
</div>


