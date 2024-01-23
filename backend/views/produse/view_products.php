<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use \yii\bootstrap5\Modal;

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
$style = <<< CSS
.loading-overlay {
//  position: absolute;
//  top: 0;
//  left: 0;
  padding:40px;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;  
 // z-index: 9999;
}

.loading-spinner {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 100px;
  height: 100px;
  animation: spin 1s linear infinite;
//  position: absolute;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.cart-meal-amount{
        width:40px;
}
.cart-meal-price{
        width:80px;
}
.cart-meal-name{
        width:45%;
}
        
        .slick-dots {
  display: none !important;
}
.cos {
     position:relative;
   //   width:inherit;
    //max-width:32%;
    // margin-right:20px;
     font-size:20px;
}
.cos-header {
        text-align:center;
        font-weight:bold;
        padding:20px;
        border-bottom:1px solid #cecece;
    }
.cos-footer{
        border-top:1px solid #cecece;
        padding-left:20px;
        padding-right:20px;
        padding-top:20px;
   }
.cos-content{
        padding:20px;
}
.test{
        display:none;
}
        
.edit-delete{
    font-family: inherit;
    font-size: 100%;
    vertical-align: baseline;
    border: 0;
    outline: 0; 
    cursor: pointer;
    float: left;
    margin-right:5px;
    background-color:white;
    border:1px solid #cecece;
    border-radius:5px;
   }
.cart-delete-button{
font-family: inherit;
    font-size: 100%;
    vertical-align: baseline;
    border: 0;
    outline: 0; 
    cursor: pointer;
    float: left;
    margin-right:5px;
    background-color:white;
   }        
.cart-row{
    display:flex;
    justify-content:space-between;
    align-items: center;
}
.red {
   color:red;
   font-size:15px;
}
.cart-single-meal{
   padding-top:5px;
   padding-bottom:5px;
}
.meal-json{
    display:none;
}
button.cart-delete-button i {
        font-size:25px;
}
button.remove {
        width:40px;
        height:40px;
        background-color:red;
        color:white;
}
button.add{
        width:40px;
        height:40px;
         background-color:green;
        color:white;
}
.price-span{
        border:1px solid #cecece;
        border-radius:5px;
        padding:5px;
        margin-left:5px;
.carousel {
  width: 80%; /* Adjust the width as needed */
  margin: 50px auto; /* Center the carousel */
}
        
.carousel div {
  background: #ddd;
  text-align: center;
  padding: 20px;
  margin: 5px;
  border-radius: 5px;
}
} 
CSS;
$this->registerCss($style);

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
   const loadingOverlay = $('#loadingOverlay');
   window.onload = function() {
       $.ajax({// create an AJAX call... // get the form data
                type: 'GET', // GET or POST
                url: '$urlIncarcareSesiune', // the file to call
                success: function (data) { // on success..
                    
                    json = JSON.parse(data);
                    console.log("aici");
                        console.log(json);
                    
                    json.forEach(function(element) {
                        for (let i=0;i<element.cantitate;i++){
                            json1 = JSON.stringify(element.date_produs);
                            incarcareProduse(element.date_produs, json1);
                        }
                    });  
                       
            loadingOverlay.hide();
         $('.test').show();    
                },
        complete: function(jqXHR, textStatus){
           
        }
      
        });
     
    };
        
        
        
    function incarcareProduse(y, abc){
      //  console.log(y);
        const linie={id:y.id,cantitate:1,denumire:y.nume,pret:y.pret_curent,json:abc};
        let existent=false;
        linii=linii.map((l)=>{
            if(l.id===linie.id){
                existent=true;
                return {...l,cantitate:l.cantitate+1,pret:parseFloat(y.pret_curent*(l.cantitate+1)).toFixed(2)};
            }
            return l;
        });
        if(!existent){
            linii.push(linie);
        }
        const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
        const xxx=verificareTotal(x);
        console.log('x=',x);
        if(x>0){
            $('#sum').show();
            $('.cart-sum-price').text(xxx.toFixed(2)+' Lei');
            $('.cart-sum-price-sub').text(x.toFixed(2)+' Lei');
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
        }
        $('#cos-list').html(linii.map(Item));
    }
        
        
        
        
        
        
   $('.btn-app').on('click',function(){
        const tabId=$(this).attr('href');
        console.log('salut');
        const categorieMare=$(this).attr('data-id');
        console.log(categorieMare);
       $.ajax({// create an AJAX call...
                data: {'categorieMare':categorieMare}, // get the form data
                type: 'GET', // GET or POST
                url: '$urlProduse', // the file to call
                success: function (data) { // on success..
                    $(tabId+' > .box-body').html(data);
                    //$.pjax.reload({container: '#lista_produse'});
                }
        });
   });
        
   $('#subcategorii_content').on('click','.taba',function(){
       const tabId=$(this).attr('href');
      //  console.log('salut');
       const categorie=$(this).attr('data-id');
        //alert($('#search-form').attr('action'));
        $.ajax({// create an AJAX call...
                data: {'categorie':categorie}, // get the form data
                type: 'GET', // GET or POST
                url: '$urlProduse', // the file to call
                success: function (data) { // on success..
                    $(tabId+' > .box-body').html(data);
                    //$.pjax.reload({container: '#lista_produse'});
                }
        });
        });
   function verificareTotal(x){
        let livrare = 0;
        if (x>=$comandaMinima){
            livrare = 0;
            
        }
        else{
            livrare=$pretLivrare;
        }
         $('.cart-sum-price-livrare').text(livrare.toFixed(2)+' Lei');
        return x+livrare;
   }
   $('.cos').on('click', '.cart-delete-button',function(){
        const id=$(this).parent().attr('data-id');
        const selector=`div[data-key='\${id}']`;
        let index = linii.findIndex(x => x.id ==id);
        const l = linii[index];
        const product=JSON.parse(l.json);
     //   console.log(product);
            const bearerToken = '$authKey';
                $.ajax({
                    type: "POST",
                    url: '$produsSesiune',
                    data: {
                        id: '$idUser',
                        produs: product.id,
                        cantitate: -l.cantitate
                    },
                    beforeSend: function(xhr) {
                    // Set the Authorization header with the Bearer token
                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
        $(this).parent().parent().remove(); 
        //console.log($(this).parent().parent());
        linii.splice(index, 1);
        if(linii.length>0){
            const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
            const xxx=verificareTotal(x);
            if(x>0){
                $('#sum').show();
                $('.cart-sum-price-sub').text((xxx).toFixed(2)+' Lei');
                $('.cart-sum-price').text(x.toFixed(2)+' Lei');
            }
            $('#cos-list').html(linii.map(Item));
        
        }
        else{
            $('#sum').hide();
            $('#btn-comanda').removeClass('btn-danger');
            $('#btn-comanda').addClass('btn-default disabled');
            $('#cos-list').html('<div class="empty">No results found.</div>');
        }
    });
   $('.cos').on('click', '.remove',function(){
        const id=$(this).parent().parent().attr('data-id');
        const selector=`div[data-key='\${id}']`;
        let index = linii.findIndex(x => x.id ==id);
        const l = linii[index];
        const product=JSON.parse(l.json);
        linii=linii.map((l)=>{
            if(l.id===product.id){
                const bearerToken = '$authKey';
                $.ajax({
                    type: "POST",
                    url: '$produsSesiune',
                    data: {
                        id: '$idUser',
                        produs: product.id,
                        cantitate: -1
                    },
                    beforeSend: function(xhr) {
                    // Set the Authorization header with the Bearer token
                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return {...l,cantitate:l.cantitate-1,pret:parseFloat(product.pret_curent*(l.cantitate-1)).toFixed(2)};
            }
            return l;
        }).filter(item => {
            if(item.cantitate <= 0){
                return false;
            }else{
                return true;
            }
        });;
        if(linii.length>0){
            $('#cos-list').html(linii.map(Item));
            const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
            const xxx=verificareTotal(x);
            if(x>0){
                $('#sum').show();
                $('.cart-sum-price').text(xxx.toFixed(2)+' Lei');
                $('.cart-sum-price-sub').text(x.toFixed(2)+' Lei');
                $('#btn-comanda').removeClass('disabled btn-default');
                $('#btn-comanda').addClass('btn-danger');
            }
        }
        else{
            $('#sum').hide();
            $('#cos-list').html('<div class="empty">No results found.</div>');
            $('#btn-comanda').removeClass('btn-danger');
            $('#btn-comanda').addClass('btn-default disabled');
        }
    });
     $('.cos').on('click', '.add',function(){
        const id=$(this).parent().parent().attr('data-id');
        const selector=`div[data-key='\${id}']`;
        let index = linii.findIndex(x => x.id ==id);
        const l = linii[index];
        const product=JSON.parse(l.json);
        linii=linii.map((l)=>{
            
//            const bearerToken = '$authKey';
//            $.ajax({
//                    type: "GET",
//                    url: '$verificaStoc?id='+product.id,
//                    beforeSend: function(xhr) {
//                    // Set the Authorization header with the Bearer token
//                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
//                    },
//                    success: function (data) {
//                        console.log(data);
//                    },
//                    error: function (error) {
//                        console.log(error);
//                    }
//                });
            if(l.id===product.id){
        const bearerToken = '$authKey';
                $.ajax({
                    type: "POST",
                    url: '$produsSesiune',
                    data: {
                        id: '$idUser',
                        produs: product.id,
                        cantitate: 1
                    },
                    beforeSend: function(xhr) {
                    // Set the Authorization header with the Bearer token
                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
                return {...l,cantitate:l.cantitate+1,pret:parseFloat(product.pret_curent*(l.cantitate+1)).toFixed(2)};
            }
            return l;
        });
        console.log(linii);
        $('#cos-list').html(linii.map(Item));
        const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
        const xxx=verificareTotal(x);
        if(x>0){
            $('#sum').show();
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
            $('.cart-sum-price').text(xxx.toFixed(2)+' Lei');
            $('.cart-sum-price-sub').text(x.toFixed(2)+' Lei');
        }
    });
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
    $('#subcategorii_content').on('click','.left-corner',function(){
        let product=JSON.parse($(this).parent().parent().find('.meal-json').text());
        const json1=$(this).parent().parent().find('.meal-json').text();
        const linie={id:product.id,cantitate:1,denumire:product.nume,pret:product.pret_curent,json:json1};
        let existent=false;
        const bearerToken = '$authKey';
                $.ajax({
                    type: "POST",
                    url: '$produsSesiune',
                    data: {
                        id: '$idUser',
                        produs: product.id,
                        cantitate: 1
                    },
                    beforeSend: function(xhr) {
                    // Set the Authorization header with the Bearer token
                        xhr.setRequestHeader('Authorization', 'Bearer ' + bearerToken);
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
        linii=linii.map((l)=>{
            if(l.id===linie.id){
                existent=true;
                return {...l,cantitate:l.cantitate+1,pret:parseFloat(product.pret_curent*(l.cantitate+1)).toFixed(2)};
            }
            return l;
        });
        if(!existent){
            linii.push(linie);
        }
        const x = linii.reduce((a, b) => (a + parseFloat(b.pret)), 0.00);
        const xxx=verificareTotal(x);
        console.log('x=',x);
        if(x>0){
            $('#sum').show();
            $('.cart-sum-price').text(xxx.toFixed(2)+' Lei');
            $('.cart-sum-price-sub').text(x.toFixed(2)+' Lei');
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
        }
        $('#cos-list').html(linii.map(Item));
    });
    let timer;
    let timeout=1000;
    $('#$searchId').on('input',function(){
        //console.log($(this).serialize());
       // console.log('am apasat');
        if($(this).val().length >= 2){ 
        console.log('epic');
        // timer=setTimeout(function(){
                $.ajax({// create an AJAX call...
                data: $(this).serialize(), // get the form data
                type: $(this).attr('method'), // GET or POST
                url: $(this).attr('action'), // the file to call
                success: function (data) { // on success..
                    document.getElementById("rezultate-cautare1").style.display="block";
                    $("a[class*='taba'][class*='active']").removeClass('active');
                    $("div[class*='tab-pane'][class*='active']").removeClass('active');
                    $('#rezultate-cautare').addClass('active');
                    $("a[data-id*='-1']").addClass('active');
                    //$('.taba').first().click();
                    $(".tab-pane.active .box-body").html(data);
                  //  $.pjax.reload({container: '#lista_produse'});
                }
            });
          //      },timeout);
            
        }else{
            $('#rezultate-cautare').removeClass('active');
            $("a[data-id*='-1']").removeClass('active');
            $('#rezultate-cautare1').removeClass('active');
            document.getElementById("rezultate-cautare1").style.display="none";
            $('.taba').eq(1).click();
        }
    });
    $('.taba').eq(1).click();
    $('#btn-comanda').on('click',function(){
        $("#confirmation-modal").modal("show");
        const items=[];
        var fd = new FormData();  
        let x=0;
        $('.cart-row').each(function() {
            if($(this).attr('data-id')){
                const produsKey=`LinieComanda[\${x}][produs]`;
                fd.append(produsKey,$(this).attr('data-id'));
                const cantitateKey=`LinieComanda[\${x}][cantitate]`;
                fd.append(cantitateKey,$(this).attr('data-cantitate'));
                const pretKey=`LinieComanda[\${x}][pret]`;
                fd.append(pretKey,$(this).attr('data-pret'));
                
                //items.push({'':$(this).attr('data-id'),'LinieComanda[\${x}][cantitate]':$(this).attr('data-cantitate'),'LinieComanda[\${x}][pret]':$(this).attr('data-pret')});
                x++;
            }
        });
        console.log(fd);
        $.ajax({// create an AJAX call...
                data: fd, // get the form data
                type: 'POST', // GET or POST
                processData: false,
                contentType: false,
                url: '$urlCreazaComanda', // the file to call
                success: function (data) { // on success..
                    //$(tabId+' > .box-body').html(data);
                    //$.pjax.reload({container: '#lista_produse'});
                }
        });
    });
        
    $('#btn-confirma').on('click',function(){
        var textAdresa = $('#text-area-adresa').val();
        var textMentiuni = $('#text-area-mentiuni').val();
        $.ajax({// create an AJAX call...
                data: {'mentiuni':textMentiuni,'adresa':textAdresa}, // get the form data
                type: 'POST', // GET or POST
              //  contentType: false,
                //processData: false,
                url: '$urlCreazaComanda', // the file to call
                success: function (data) { // on success..
//                    $(tabId+' > .box-body').html(data);
                    console.log('a mers');
                }
        });
//        $.ajax({// create an AJAX call...
//                data: fd, // get the form data
//                type: 'POST', // GET or POST
//                processData: false,
//                contentType: false,
//                url: '$urlCreazaComanda', // the file to call
//                success: function (data) { // on success..
//                    //$(tabId+' > .box-body').html(data);
//                    //$.pjax.reload({container: '#lista_produse'});
//                }
//        });
    });
        
    $('.slick-slide').on('click',function(){
       let id = $(this).attr('data-id');
        $.ajax({// create an AJAX call...
                data: {'idCategorie':id}, // get the form data
                type: 'GET', // GET or POST
                url: '$urlSchimba', // the file to call
                success: function (data) { // on success.
                    console.log(data);
                    $('#subcategorii_content').html(data);
                        $('.taba').eq(1).click();

                }
        });
    });
    
     $(".kioskboard-row").on('click','.kioskboard-key',function(){
        alert('test');
    // var keyValue=$(this).attr("data-value");
    // alert(keyValue);
   });   
        
    const socket = io('http://localhost:8000', { transports: ['websocket', 'polling'] });
    socket.on('previous-orders', (data) => {
        console.log('Previous Orders:', data);
            $("#text-nr-telefon").val(data);
        $.ajax({// create an AJAX call...
                data: {'telefon':data}, // get the form data
                type: 'GET', // GET or POST
                url: '$urlComenzi', // the file to call
                success: function (data) { // on success.
                    console.log(data);
                    $('#istoric').append(data);
                }
        });
        
    });
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
                            'itemView' => '_linie_comanda'
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


