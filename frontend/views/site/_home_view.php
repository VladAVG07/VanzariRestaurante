<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
use kartik\touchspin\TouchSpin;
use yii\helpers\Url;

$assetDir = PizzGhAsset::register($this);

$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');
$urlProdus = Url::toRoute('site/afiseaza-produs');

$formatJs = <<< SCRIPT
        $('.menu-nav-link').on('click',function(){
        
            let categorie = $(this).attr('data-id');
            $.ajax({
                data: {'idCategorie': categorie},
                type: 'GET',
                url: '$urlCategorie', 
                success: function (data) {
                    $('#v-pills-tabContent').html(data);
                }
            });
        });
        $(document).on('click', '.cart-button', function(e){
            console.log('Cos apasat');
            $('#mymodal').modal('show');
        });
        $(document).on('click','.btn-meniu',function(e){
            e.preventDefault();
            let produs = $(this).attr('data-id');
            $('#modalProdus').modal('show');
            
            $.ajax({
                data: {'idProdus': produs},
                type: 'GET',
                url: '$urlProdus', 
                success: function (data) {
                    $('.modal-produs-content').html(data);
                }
            });
        });
        
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);

Modal::begin([
    'title' => '<h4>Detalii produs</h4>',
    'id' => 'modalProdus',
    'size' => 'modal-lg', // You can use 'lg', 'sm', or 'xl' for large, small, or extra-large modal
    'options' => [
        'class' => 'custom-modal-bg', // Add your custom CSS class here
    ],
]);
?>
<div class="modal-content">
    <div class="modal-body">
        <div class="modal-produs">
            <div class="ftco-animate fadeInUp ftco-animated modal-produs-content" data-id="7">

            </div>
        </div>
    </div>
    <div class="modal-footer">
    <?=Html::button('Adaugă în coș', ['class' => ['btn', 'btn-primary p-3 px-xl-4 py-xl-3'], 'data-dismiss' => 'modal'])?>
    </div>
</div>
<?php
Modal::end();
?>

<?php
Modal::begin([
    'title' => '<h4>Coșul meu</h4>',
    'id' => 'mymodal',
    'size' => 'modal-lg', // You can use 'lg', 'sm', or 'xl' for large, small, or extra-large modal
    'options' => [
        'class' => 'custom-modal-bg', // Add your custom CSS class here
    ],
]);

echo '<div class="modal-content">';
echo '   <div class="modal-body">';
?>
<div class="cart-items item">
    <ul class="list-unstyled">
        <li class="item">
            <div class="d-flex ftco-animate fadeInUp ftco-animated" data-id="7">
                <div class="desc">
                    <div class="d-flex text align-items-center">
                        <div class="col-md-8">
                            <h5><span>Pizza traditionala</span></h5>
                        </div>
                        <div class="col-md-2 cos-produs align-items-center">
                            <?php
                            echo TouchSpin::widget([
                                'name' => 't6',
                                'options' => ['class' => 'cos-produs-input'],

                                'pluginOptions' => [
                                    'initval' => 1,
                                    'min' => 1,
                                    'max' => 100,
                                    'buttonup_class' => 'h-50 btn btn-block btn-sm btn-primary',
                                    'buttondown_class' => 'h-50 btn btn-sm btn-info',
                                    'buttonup_txt' => '+',
                                    'buttondown_txt' => '-',
                                    // 'verticalbuttons' => true
                                ]
                            ]);
                            ?>
                        </div>
                        <div class="col-md-2"><span class="price">2.00 RON</span></div>
                    </div>
                </div>
            </div>
        </li>
        <li class="item">
            <div class="d-flex ftco-animate fadeInUp ftco-animated" data-id="7">
                <div class="desc">
                    <div class="d-flex text align-items-center">
                        <div class="col-md-8">
                            <h5><span>Pizza traditionala</span></h5>
                        </div>
                        <div class="col-md-2  cos-produs align-items-center">
                            <!-- <div class="input-group number-spinner  align-items-center">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary p-3 px-xl-4 py-xl-3" data-dir="dwn">-</button>
                                </span>
                                <input type="text" class="form-control text-center" value="1">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary p-3 px-xl-4 py-xl-3" data-dir="up">+</button>
                                </span>
                            </div>
                        </div> -->
                            <?php
                            echo TouchSpin::widget([
                                'name' => 't6',
                                'options' => ['class' => 'cos-produs-input'],

                                'pluginOptions' => [
                                    'initval' => 1,
                                    'min' => 1,
                                    'max' => 100,
                                    'buttonup_class' => 'h-50 btn btn-block btn-sm btn-primary',
                                    'buttondown_class' => 'h-50 btn btn-sm btn-info',
                                    'buttonup_txt' => '+',
                                    'buttondown_txt' => '-',
                                    // 'verticalbuttons' => true
                                ]
                            ]);
                            ?>
                            
                        </div>
                        <div class="col-md-2"><span class="price">2.00 RON</span></div>
                    </div>
                </div>
        </li>
    </ul>
</div>
<?php
echo '   </div>';
echo '   <div class="modal-footer">';
echo Html::button('Close', ['class' => ['btn', 'btn-primary p-3 px-xl-4 py-xl-3'], 'data-dismiss' => 'modal']);
echo '   </div>';
echo '</div>';

Modal::end();
?>
<section class="home-slider owl-carousel img" style="background-image: url(<?= $assetDir->baseUrl ?>/images/bg_1.jpg);">
    <div class="slider-item">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text align-items-center" data-scrollax-parent="true">

                <div class="col-md-6 col-sm-12 ftco-animate">
                    <span class="subheading">Delicious</span>
                    <h1 class="mb-4">Italian Cuizine</h1>
                    <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                    <p><a href="#" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-meniu']) ?>" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
                </div>
                <div class="col-md-6 ftco-animate">
                    <img src="<?= $assetDir->baseUrl ?>/images/bg_1.png" class="img-fluid" alt="">
                </div>

            </div>
        </div>
    </div>

    <div class="slider-item">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text align-items-center" data-scrollax-parent="true">

                <div class="col-md-6 col-sm-12 order-md-last ftco-animate">
                    <span class="subheading">Crunchy</span>
                    <h1 class="mb-4">Italian Pizza</h1>
                    <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                    <p><a href="#" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-meniu']) ?>" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
                </div>
                <div class="col-md-6 ftco-animate">
                    <img src="<?= $assetDir->baseUrl ?>/images/bg_2.png" class="img-fluid" alt="">
                </div>

            </div>
        </div>
    </div>

    <div class="slider-item" style="background-image: url(<?= $assetDir->baseUrl ?>/images/bg_3.jpg);">
        <div class="overlay"></div>
        <div class="container">
            <div class="row slider-text justify-content-center align-items-center" data-scrollax-parent="true">

                <div class="col-md-7 col-sm-12 text-center ftco-animate">
                    <span class="subheading">Welcome</span>
                    <h1 class="mb-4">We cooked your desired Pizza Recipe</h1>
                    <p class="mb-4 mb-md-5">A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
                    <p><a href="#" class="btn btn-primary p-3 px-xl-4 py-xl-3">Order Now</a> <a href="<?= Yii::$app->urlManager->createUrl(['site/pagina-meniu']) ?>" class="btn btn-white btn-outline-white p-3 px-xl-4 py-xl-3">View Menu</a></p>
                </div>

            </div>
        </div>
    </div>
</section>
<section class="ftco-menu">
    <div class="container-fluid">
        <div class="row d-md-flex">
            <!--<div class="col-lg-4 ftco-animate img f-menu-img mb-5 mb-md-0" style="background-image: url(<?= $assetDir->baseUrl ?>/images/about.jpg);">-->
        </div>
        <div class="col-lg-12 ftco-animate p-md-5">
            <div class="row">
                <div class="col-md-12 nav-link-wrap mb-5">
                    <div class="nav ftco-animate nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <?php
                        $restaurantId = 7;
                        $categorii = \backend\models\Categorii::find()
                            ->innerJoin('produse p', 'p.categorie = categorii.id')
                            ->innerJoin('restaurante_categorii', 'categorii.id = restaurante_categorii.categorie')
                            ->where(['restaurante_categorii.restaurant' => $restaurantId])->andWhere(['<>', 'categorii.parinte', 'null'])
                            ->andWhere(['<>', 'categorii.nume', 'Servicii'])
                            ->all();

                        foreach ($categorii as $categorie) {
                            $parinte = \backend\models\Categorii::findOne(['id' => $categorie->parinte]);
                            $nume = $parinte->nume . ' ' . $categorie->nume;
                            echo Html::a($nume, '#v-pills-' . $categorie->id, [
                                'id' => 'v-pills-' . $categorie->id . '-tab',
                                'class' => 'nav-link',
                                'data-toggle' => 'pill',
                                'role' => 'tab',
                                'aria-controls' => 'v-pills-' . $categorie->id,
                                'aria-selected' => 'false',
                                'data-id' => $categorie->id,
                            ]);
                        }
                        ?>
                        <!--<a class="nav-link" id="v-pills-4-tab" data-toggle="pill" href="#v-pills-4" role="tab" aria-controls="v-pills-4" aria-selected="false">Pasta</a>-->
                    </div>
                </div>
                <div class="col-md-12 align-items-center">

                    <div class="tab-content ftco-animate" id="v-pills-tabContent">

                        <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/pizza-2.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/pizza-3.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-2" role="tabpanel" aria-labelledby="v-pills-2-tab">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/drink-1.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Lemonade Juice</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/drink-2.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Pineapple Juice</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/drink-3.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Soda Drinks</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-3" role="tabpanel" aria-labelledby="v-pills-3-tab">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/burger-1.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/burger-2.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/burger-3.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-4" role="tabpanel" aria-labelledby="v-pills-4-tab">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/pasta-1.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/pasta-2.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="menu-wrap">
                                        <a href="#" class="menu-img img mb-4" style="background-image: url(<?= $assetDir->baseUrl ?>/images/pasta-3.jpg);"></a>
                                        <div class="text">
                                            <h3><a href="#">Itallian Pizza</a></h3>
                                            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia.</p>
                                            <p class="price"><span>$2.90</span></p>
                                            <p><a href="#" class="btn btn-white btn-outline-white">Add to cart</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>