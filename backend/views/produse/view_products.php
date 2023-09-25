<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use  \yii\bootstrap5\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\Produse */
/* @var $form \kartik\form\ActiveForm */

$this->params['breadcrumbs'][] = ['label' => 'Produses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$searchId = Html::getInputId($model, 'denumire');
$urlSearch = '';

$urlProduse = \yii\helpers\Url::toRoute('produse/proceseaza-comanda');
$urlCreazaComanda = \yii\helpers\Url::toRoute('comenzi/create');

$style = <<< CSS
.cart-meal-amount{
        width:40px;
}
.cart-meal-price{
        width:80px;
}
.cart-meal-name{
        width:45%;
}
.cos {
    // position:fixed;
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
}        
CSS;
$this->registerCss($style);
$formatJs = <<< SCRIPT
   let linii=[];
       
   $('.nav-tabs').on('click','.taba',function(){
       const tabId=$(this).attr('href');
        console.log('tabId'+tabId);
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
   $('.cos').on('click', '.cart-delete-button',function(){
        $(this).parent().parent().remove(); 
        let id=parseInt($(this).parent().attr('data-id'));
        linii=linii.filter((l)=>{
            return l.id!==id;
        });
        if(linii.length>0){
            const x=linii.reduce((a, b) => (a + b.pret),0);
            if(x>0){
                $('#sum').show();
                $('.cart-sum-price').text(x+' Lei');
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
        const product=JSON.parse($(selector).find('.meal-json').text());
        linii=linii.map((l)=>{
            if(l.id===product.id){
                return {...l,cantitate:l.cantitate-1,pret:product.pret*(l.cantitate-1)};
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
            const x=linii.reduce((a, b) => (a + b.pret),0);
            if(x>0){
                $('#sum').show();
                $('.cart-sum-price').text(x+' Lei');
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
        console.log('[DEBUG] Proceseaza comanda addProductInCos',id);
        const selector=`div[data-key='\${id}']`;
        const product=JSON.parse($(selector).find('.meal-json').text());
        linii=linii.map((l)=>{
            if(l.id===product.id){
                return {...l,cantitate:l.cantitate+1,pret:product.pret*(l.cantitate+1)};
            }
            return l;
        });
        $('#cos-list').html(linii.map(Item));
        const x=linii.reduce((a, b) => (a + b.pret),0);
        if(x>0){
            $('#sum').show();
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
            $('.cart-sum-price').text(x+' Lei');
        }
    });
    const Item=({id,cantitate,denumire,simbol,pret})=>`
        <div data-key='\${id}'> 
        <div class="cart-single-meal">
            <div class="cart-row" data-restaurant="1" data-id='\${id}' data-cantitate='\${cantitate}' data-pret='\${pret}'>
                <span class="cart-meal-amount"><span class="amount">\${cantitate}</span>x</span>
                <span class="cart-meal-name">\${denumire}</span>
                <div class="cart-meal-edit-buttons">
                    <button class="remove edit-delete" onclick="">-</button>
                    <button class="add edit-delete">+</button>
                </div>
                <span class="cart-meal-price"><span class="price">\${pret}</span> \${simbol}</span>
                <button class="cart-delete-button"><i class="fa  fa-trash-o red"></i></button>
            </div>
        </div>
        </div>`;
    $('.tab-content').on('click','.left-corner',function(){
        let product=JSON.parse($(this).parent().parent().find('.meal-json').text());
        const linie={id:product.id,cantitate:1,denumire:product.denumire,simbol:product.simbol,pret:product.pret};
        let existent=false;
        linii=linii.map((l)=>{
            if(l.id===linie.id){
                existent=true;
                return {...l,cantitate:l.cantitate+1,pret:product.pret*(l.cantitate+1)};
            }
            return l;
        });
        if(!existent){
            linii.push(linie);
        }
        const x=linii.reduce((a, b) => (a + b.pret),0);
        if(x>0){
            $('#sum').show();
            $('.cart-sum-price').text(x+' Lei');
            $('#btn-comanda').removeClass('disabled btn-default');
            $('#btn-comanda').addClass('btn-danger');
        }
        $('#cos-list').html(linii.map(Item));
    });
    let timer;
    let timeout=1000;
    $('#$searchId').on('keyup',function(){
        if($(this).val().length > 2 || $(this).val().length==0){ 
        // timer=setTimeout(function(){
                $.ajax({// create an AJAX call...
                data: $(this).serialize(), // get the form data
                type: $(this).attr('method'), // GET or POST
                url: $(this).attr('action'), // the file to call
                success: function (data) { // on success..
                    $('#lista_produse').html(data);
                    //$.pjax.reload({container: '#lista_produse'});
                }
            });
          //      },timeout);
            
        }
    });
    $('.taba').first().click();
        
    $('#btn-comanda').on('click',function(){
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
        
SCRIPT;

// Register the formatting script
$this->registerJs($formatJs, yii\web\View::POS_END);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="col-sm-7">
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
                            <?php
                            echo $form->field($model, 'nume', [
                                'addon' => ['prepend' => ['content' => '<i class="fa fa-search"></i>']]
                            ])->textInput(['maxlength' => true])
                            ?>
                        </div>
                    </div>

                </div>
                <?php
                $form->end();
                ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <?php
                                $categorii = \backend\models\Categorii::find()->all();
                                $x = 0;
                                $active = 'active';
                                $expanded = true;
                                foreach ($categorii as $categorie) {
                                    $class = sprintf('class="%s"', $active);
                                    if ($x > 0) {
                                        $active = '';
                                        $expanded = false;
                                    }
                                    echo Html::tag('li', Html::a($categorie->nume, sprintf('#%s', yii\helpers\Inflector::slug($categorie->nume)), ['data-toggle' => 'tab', 'data-id' => $categorie->id, 'aria-expanded' => $expanded, 'class' => 'taba']), ['class' => $active]);

                                    $x++;
                                }
                                ?>
                            </ul>
                            <div class="tab-content">
                                <?php
                                $x = 0;
                                foreach ($categorii as $categorie) {
                                    echo Html::tag('div', Html::tag('div', '', ['class' => 'box-body']), ['class' => 'tab-pane ' . ($x > 0 ? '' : 'active'), 'id' => yii\helpers\Inflector::slug($categorie->nume)]);
                                    $x++;
                                }
                                ?>

                            </div>
                        </div>



                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="cos">
                <div class="box box-danger">
                    <div class="box-header text-center with-border">
                        <h3 class="box-title">Istoric comenzi</h3>
                        <br />
                        <h2 class="box-title">0726213098</h2>
                        <!--              <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                      </div>-->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="box box-primary">
                    <div class="box-header text-center with-border">
                        <h3 class="box-title">Cos</h3>

                        <!--              <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                      </div>-->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
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
                                <span class="cart-sum-price grey">32,00 lei</span>
                            </div><div class="cart-row js-delivery-costs-row" style="display: flex;">
                                <span class="cart-sum-name grey">Costuri de livrare</span>
                                <span class="cart-sum-price grey">4,00 lei</span>
                            </div><div class="cart-row row-sum js-total-costs-row" style="display: flex;font-weight: bold">
                                <span class="cart-sum-name">Total</span>
                                <span class="cart-sum-price">36,00 lei</span>
                            </div></div>
<?= Html::button('Comanda', ['id' => 'btn-comanda', 'class' => 'btn btn-block btn-default btn-lg disabled']) ?>

                    </div>
                    <!-- /.box-footer -->
                </div>
            </div>
        </div>
    </div>
</div>


