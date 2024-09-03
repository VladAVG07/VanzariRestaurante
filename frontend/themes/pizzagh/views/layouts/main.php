<?php
/* @var $this \yii\web\View */
/* @var $content string */

use frontend\themes\pizzagh\assets\PizzGhAsset;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
//use kartik\touchspin\TouchSpin;
use yii\helpers\Url;

//\hail812\adminlte3\assets\FontAwesomeAsset::register($this);
$assetDir = PizzGhAsset::register($this);
//\hail812\adminlte3\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');
//$this->registerCssFile('@web/assets/theme/css/theme-style.css');
//echo Yii::getAlias('@web');
$assetDir = Yii::$app->assetManager->getPublishedUrl('@webroot/css');
$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');


$urlProdus = Url::toRoute('site/afiseaza-produs');
$urlAdaugaProdus = Url::toRoute('site/adauga-in-cos');
$urlAdaugaProdusInCos = Url::toRoute('site/produs-adauga-in-cos');
$urlGetContinutCos = Url::toRoute('site/continut-cos');
$urlStergeProdus = Url::toRoute('site/sterge-din-cos');
$urlIsCosEmpty = Url::toRoute('site/is-cos-empty');
$urlInchis = Url::toRoute('site/restaurant-inchis');
$basket = \Yii::$app->session->get('basket');
if (is_null($basket)) {
    $basket = new \frontend\models\Basket();
}
$urlGenereazaModalProdus = Url::toRoute('site/genereaza-modal-produs');
$urlProceseazaComanda = Url::toRoute('site/proceseaza-comanda');
$empty = $basket !== null && count($basket->basketItems) > 0;



$csrlf = sprintf('\'%s\':\'%s\'', \Yii::$app->request->csrfParam, \Yii::$app->request->getCsrfToken());
$formatJs = <<<SCRIPT

$('a[href*="#"]').on('click', function(event) {
    var target = $(this.getAttribute('href'));
    //console.log('ggg',target);
    if (target.length) {
        event.preventDefault();
        $('html, body').stop().animate({
            scrollTop: target.offset().top-70
        }, 1000);
    }
});

$(document).on('click', '.btn-casa', function(event) {
    
   $.ajax({
        url: '$urlInchis',
        type: 'GET',
        success: function(data) {
            data1 = JSON.parse(data);
            mesajInchis = data1.message;
            if (data1.inchis === 1){
                const popup = document.getElementById('closed-popup');
                $('#pMesaj').html(mesajInchis); // Corrected the jQuery selector
                popup.classList.add('show');
            }else{
                $.ajax({
                    url: '$urlIsCosEmpty',
                    type: 'GET',
                    success: function(response) {
                        if(response != 0){
                            window.location.href = '$urlProceseazaComanda';
                        }else{
                            const popup = document.getElementById('empty-popup');
                            popup.classList.add('show');
                        }
                    },
                });
            }
        },
    });        
});

// $('.btn-casa').on('click',function(event){
//     window.location.href='$urlProceseazaComanda';
// });

// Calculate total cost of products in the shopping cart
function getTotal() {
    var total = 0.00;
    $('#cos-modal .cos-produs-input').each(function(index, el) {
        total += parseInt($(el).val()) * parseFloat($(el).attr('data-price')).toFixed(2);
    });
    console.log(total);
    $('.btn-casa').text('La casă ' + total.toFixed(2) + ' RON');
    $('.btn-casa').attr('data-total',total.toFixed(2));
}

// Show the shopping cart
function showCart() {
    $('.cart-items .item').addClass('d-none');
    $('.modal-content .justify-content-center').removeAttr('style');
    $('#cos-modal').on('hidden.bs.modal', function (e) {
      
    }).modal('show');
    $.ajax({
        url: '$urlGetContinutCos',
        type: 'GET',
        success: function(response) {
            var cosModalBody = $('#cos-modal .modal-body:last');
            $('.modal-content .justify-content-center').attr("style", "display: none !important");
            $('.cart-items .item').removeClass('d-none');
            try{
                cosModalBody.html(response);
            }catch(error){
                console.log(error);
            }
            initTouchSpin();
            getTotal();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

// Initialize TouchSpin inputs
function initTouchSpin() {
    $('.cos-produs-input').TouchSpin({
        initval: 1,
        min: 0,
        max: 100,
        buttonup_class: 'h-50 btn btn-block btn-sm btn-primary',
        buttondown_class: 'h-50 btn btn-sm btn-info',
        buttonup_txt: '+',
        buttondown_txt: '-',
    }).on('touchspin.on.startspin', function() {
        getTotal();
        var value = $(this).val();
        if (value === '0') {
            $(this).closest('.item').remove();
        }
        var idProdus = $(this).attr('data-produs');
    //    deleteProductFromCart($(this), idProdus, $(this).val());
    });
}

// Event handler for adding a product to the shopping cart
$(document).on('click', '.btn-meniu', function(e) {
    e.preventDefault();
    var idProdus = $(this).attr('data-id');
    $.ajax({
        url: '$urlGenereazaModalProdus',
        type: 'POST',
        data: {
            idProdus: idProdus,
            $csrlf
        },
        success: function(response) {
            var cosModalBody = $('#produs-modal .modal-body:last');
            cosModalBody.html(response);
            $('#produs-modal').modal('show');
            initTouchSpin();
            getTotal();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});




$(document).on('click', '#btn-adauga-in-cos', function(e) {
    e.preventDefault();
    var idProdus = $('.field-basketitem-idprodus #basketitem-idprodus').val();//.attr('data-id');
    var cantitate = $('.cos-produs-input').val();
    $.ajax({
        url: '$urlAdaugaProdusInCos',
        type: 'POST',
        data: {
            idProdus: idProdus,
            cantitate: cantitate,
            $csrlf
        },
        success: function(response) {
            /*var cosModalBody = $('#produs-modal .modal-body:last');
            cosModalBody.html(response);
            $('#produs-modal').modal('show');
            initTouchSpin();
            getTotal();*/
            showCart();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

// Event handler for changing product quantity in the shopping cart
$(document).on('change', '#cos-modal .cos-produs-input', function() {
    var value = $(this).val();
    if (value === '0') {
        $(this).closest('.item').remove();
    }
    var idProdus = $(this).attr('data-produs');
    deleteProductFromCart($(this), idProdus, $(this).val());
    console.log('change data');
    getTotal();
});

// Function to delete a product from the shopping cart
function deleteProductFromCart(input, idProdus, cantitate) {
    if (!$(input).data('ajaxRunning')) {
        $(input).data('ajaxRunning', true);
        $.ajax({
            url: '$urlAdaugaProdus',
            type: 'POST',
            data: {
                cantitate: cantitate,
                idProdus: idProdus,
                $csrlf
            },
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            },
            complete: function() {
                $(input).data('ajaxRunning', false);
            }
        });
    }
}

// // Event handler for showing the shopping cart
$(document).on('click', '.cart-button', function(e) {
    e.preventDefault();
    console.log('showCart');
    showCart();
});

// Event handler for showing products based on category
$('.menu-nav-link').on('click', function() {
    var categorie = $(this).attr('data-id');
    $.ajax({
        data: {
            'idCategorie': categorie
        },
        type: 'GET',
        url: '$urlCategorie',
        success: function(data) {
            $('#v-pills-tabContent').html(data);
        }
    });
});

        
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);

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
        <!-- Include Babel Standalone -->
        <script src="https://unpkg.com/react@17/umd/react.development.js" crossorigin></script>
        <script src="https://unpkg.com/react-dom@17/umd/react-dom.development.js" crossorigin></script>
        <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Nothing+You+Could+Do" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <?php $this->head() ?>
    </head>

    <body class="container-fluid scrollable-page">
        <?php $this->beginBody() ?>
        <?php
        Modal::begin([
            'title' => '<h4>Detalii produs</h4>',
            'id' => 'produs-modal',
            'size' => 'modal-lg', // You can use 'lg', 'sm', or 'xl' for large, small, or extra-large modal
            'options' => [
                'class' => 'custom-modal-bg', // Add your custom CSS class here
            ],
        ]);
        ?>
        <div class="modal-content">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <?= Html::button('Adaugă în coș', ['id' => 'btn-adauga-in-cos', 'class' => ['btn', 'btn-primary btn-arata-cos p-3 px-xl-4 py-xl-3'], 'data-dismiss' => 'modal']) ?>
            </div>
        </div>
        <?php
        Modal::end();
        ?>



        <?php
        Modal::begin([
            'title' => '<h4>Coșul meu</h4>',
            'id' => 'cos-modal',
            'size' => 'modal-lg', // You can use 'lg', 'sm', or 'xl' for large, small, or extra-large modal
            'options' => [
                'class' => 'custom-modal-bg', // Add your custom CSS class here
            ],
        ]);

        echo '<div class="modal-content">';
        ?>
        <div class="d-flex justify-content-center mb-3 position-relative">
            <div class="spinner-border text-primary" id="loadingSpinner" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="position-absolute top-50 start-50 translate-middle text-warning">Loading...</span>
        </div>

        <?php
        echo '   <div class="modal-body">';
        ?>

        <?php
        echo '   </div>';
        // echo '   <div class="modal-footer">';
        // echo '   </div>';
        echo '</div>';

        Modal::end();
        ?>
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