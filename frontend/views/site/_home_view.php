<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
//use kartik\touchspin\TouchSpin;
use yii\helpers\Url;

$this->title='Acasă - DioBistro Călărași';
$assetDir = Yii::$app->assetManager->getBundle('frontend\themes\pizzagh\assets\PizzGhAsset');
$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');

?>
<!-- <section class="home-slider owl-carousel img" style="background-image: url(<?= $assetDir->baseUrl ?>/images/bg_1.jpg);">
    <div class="slider-item">
        <div class="overlay"></div>
        <div class="container">
            <div class="d-flex slider-text align-items-center" data-scrollax-parent="true">

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
</section> -->



<section id="rest-acasa" class="ftco-menu">
    <div class="container-fluid">
        <div class="row d-md-flex">
            <div class="col-lg-4 ftco-animate img f-menu-img mb-5 mb-md-0" style="width: 496px; height: 575px;background-image: url(<?= $assetDir->baseUrl ?>/images/aboutdio2.jpg);">
            </div>
            <div class="col-lg-8 ftco-animate p-md-5 fadeInUp ftco-animated">
                <div class="row">
                    <div class="col-md-12 nav-link-wrap mb-5">
                        <div class="nav ftco-animate nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <?php
                            $restaurantId = 9;
                            $categorii = \backend\models\Categorii::find()
                                ->innerJoin('produse p', 'p.categorie = categorii.id')
                                ->innerJoin('restaurante_categorii', 'categorii.id = restaurante_categorii.categorie')
                                ->where(['restaurante_categorii.restaurant' => $restaurantId])
                                //->andWhere(['<>', 'categorii.parinte', 'null'])
                                ->andWhere(['<>', 'categorii.nume', 'Servicii'])
                                ->orderBy(['ordine'=>SORT_ASC,'nume'=>SORT_ASC])
                                ->all();
                            //var_dump($categorii);
                            //exit();
                            $i = 0;
                            $idCategorie = -1;
                            foreach ($categorii as $categorie) {
                                $parinte = \backend\models\Categorii::findOne(['id' => $categorie->parinte]);
                                $nume = $categorie->nume;
                                if (!is_null($parinte))
                                    $nume = $parinte->nume . ' ' . $categorie->nume;
                                if ($i == 0)
                                    $idCategorie = $categorie->id;
                                    echo "<!-- Categoria  $nume-->";
                                echo Html::a($nume, '#v-pills-' . $categorie->id, [
                                    'id' => 'v-pills-' . $categorie->id . '-tab',
                                    'class' => sprintf('%s %s', 'nav-link menu-nav-link', $i == 0 ? 'active' : ''),
                                    'data-toggle' => 'pill',
                                    'role' => 'tab',
                                    'aria-controls' => 'v-pills-' . $categorie->id,
                                    'aria-selected' => 'false',
                                    'data-id' => $categorie->id,
                                ]);
                                $i++;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12 align-items-center">

                        <div class="tab-content ftco-animate" id="v-pills-tabContent">
                            <?php
                            echo $this->render('_categorie_view', ['id' => $idCategorie]);
                            ?>
                            <!-- <div class="tab-pane fade show active" id="v-pills-1" role="tabpanel" aria-labelledby="v-pills-1-tab">
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
                        </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="rest-menu" class="ftco-section">
    <?= $this->render('_meniu_view') ?>
</section>

<section id="rest-program" class="ftco-intro">
    <div class="container-wrap">
        <div class="wrap d-md-flex">
            <div class="info" style="width:100% !important">
                <div class="row no-gutters">
                    <div class="col-md-3 d-flex ftco-animate">
                        <div class="icon"><span class="icon-phone"></span></div>
                        <div class="text">
                            <h3>+40722 885 551</h3>
                            <p>DIO Bistro este locul unde tu și prietenii tăi o să vă simțiți minunat!</p>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex ftco-animate">
                        <div class="icon"><span class="icon-my_location"></span></div>
                        <div class="text">
                            <h3>Prelungirea București numărul 123</h3>
                            <p>Călărași, România</p>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex ftco-animate">
                        <div class="icon"><span class="icon-clock-o"></span></div>
                        <div class="text">
                            <h3>Luni - Vineri</h3>
                            <p>08:00 - 22:00</p>
                        </div>
                        <div class="text">
                            <h3>Sâmbătă - Duminică</h3>
                            <p>10:00 - 22:00</p>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex ftco-animate">
                        <div class="icon"><span class="icon-clock-o"></span></div>
                        <div class="text">
                            <h3>Luni - Vineri</h3>
                            <p>08:00 - 22:00</p>
                        </div>
                        <div class="text">
                            <h3>Sâmbătă - Duminică</h3>
                            <p>10:00 - 22:00</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="social d-md-flex pl-md-5 p-4 align-items-center"> -->
                <!-- <ul class="social-icon"> -->
                    <!-- <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li> -->
                    <!-- <li class="ftco-animate"><a href="https://web.facebook.com/people/Dio-Bistro/100086182524636/"><span class="icon-facebook"></span></a></li> -->
                    <!-- <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li> -->
                <!-- </ul> -->
            <!-- </div> -->
        </div>
    </div>
</section>
<!-- <section class="ftco-about d-md-flex">
    	<div class="one-half img" style="background-image: url(<?= $assetDir->baseUrl ?>/images/about.jpg);"></div>
    	<div class="one-half ftco-animate">
        <div class="heading-section ftco-animate ">
          <h2 class="mb-4">Welcome to <span class="flaticon-pizza">Pizza</span> A Restaurant</h2>
        </div>
        <div>
  				<p>On her way she met a copy. The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word "and" and the Little Blind Text should turn around and return to its own, safe country. But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her, made her drunk with Longe and Parole and dragged her into their agency, where they abused her for their.</p>
  			</div>
    	</div>
    </section> -->

<section class="ftco-counter ftco-bg-dark img" id="section-counter" style="background-image: url(<?= $assetDir->baseUrl ?>/images/bg_2.jpg);" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-pizza-1"></span></div>
                                <strong class="number" data-number="5432">0</strong>
                                <span>Pizza livrate</span>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		              	<div class="icon"><span class="flaticon-medal"></span></div>
		              	<strong class="number" data-number="85">0</strong>
		              	<span>Number of Awards</span>
		              </div>
		            </div>
		          </div> -->
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-laugh"></span></div>
                                <strong class="number" data-number="1056">0</strong>
                                <span>Clienți mulțumiți</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                            <div class="text">
                                <div class="icon"><span class="flaticon-chef"></span></div>
                                <strong class="number" data-number="14">0</strong>
                                <span>Angajați</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="rest-contact" class="ftco-appointment">
			<div class="overlay"></div>
    	<div class="container-wrap">
    		<div class="row no-gutters d-md-flex align-items-center">
    			<div class="col-md-6 d-flex align-self-stretch">
    				<div id="map"></div>
    			</div>
	    		<div class="col-md-6 appointment ftco-animate">
	    			<h3 class="mb-3">Contactează-ne</h3>
	    			<form action="#" class="appointment-form">
	    				<div class="d-md-flex">
		    				<div class="form-group">
		    					<input type="text" class="form-control" placeholder="Nume">
		    				</div>
	    				</div>
	    				<div class="d-me-flex">
	    					<div class="form-group">
		    					<input type="text" class="form-control" placeholder="Prenume">
		    				</div>
	    				</div>
	    				<div class="form-group">
	              <textarea name="" id="" cols="30" rows="10" class="form-control" placeholder="Mesajul dumneavoastră"></textarea>
	            </div>
	            <div class="form-group">
	              <input type="submit" value="Trimite" class="btn btn-primary py-3 px-4">
	            </div>
	    			</form>
	    		</div>    			
    		</div>
    	</div>
    </section>