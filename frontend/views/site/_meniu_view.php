<?php

use yii\helpers\Html;

$restaurantId = 10;
$categorii = \backend\models\Categorii::find()
        ->innerJoin('produse p', 'p.categorie = categorii.id')
        ->innerJoin('restaurante_categorii', 'categorii.id = restaurante_categorii.categorie')
        ->where(['restaurante_categorii.restaurant' => $restaurantId])
        //->andWhere(['<>', 'categorii.parinte', 'null'])
        ->andWhere(['<>', 'categorii.nume', 'Servicii'])
        ->orderBy(['ordine'=>SORT_ASC])
        ->all();

// $urlAdaugaProdusInCos = Url::toRoute('site/produs-adauga-in-cos');
// $urlGetContinutCos = Url::toRoute('site/continut-cos');
// $urlStergeProdus = Url::toRoute('site/sterge-din-cos');

$csrlf = sprintf('\'%s\':\'%s\'', \Yii::$app->request->csrfParam, \Yii::$app->request->getCsrfToken());
?>
<!--<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section ftco-animate text-center">
                <h2 class="mb-4">Our Menu</h2>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
            </div>
        </div>
    </div>
    <div class="container-wrap">
        <div class="row no-gutters d-flex">
            <div class="col-lg-4 d-flex ftco-animate">
                <div class="services-wrap d-flex">
                    <a href="#" class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-1.jpg);"></a>
                    <div class="text p-4">
                        <h3>Italian Pizza</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia </p>
                        <p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex ftco-animate">
                <div class="services-wrap d-flex">
                    <a href="#" class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-2.jpg);"></a>
                    <div class="text p-4">
                        <h3>Greek Pizza</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
                        <p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex ftco-animate">
                <div class="services-wrap d-flex">
                    <a href="#" class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-3.jpg);"></a>
                    <div class="text p-4">
                        <h3>Caucasian Pizza</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
                        <p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-flex ftco-animate">
                <div class="services-wrap d-flex">
                    <a href="#" class="img order-lg-last" style="background-image: url(../themes/pizza-gh/web/images/pizza-4.jpg);"></a>
                    <div class="text p-4">
                        <h3>American Pizza</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia </p>
                        <p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex ftco-animate">
                <div class="services-wrap d-flex">
                    <a href="#" class="img order-lg-last" style="background-image: url(../themes/pizza-gh/web/images/pizza-5.jpg);"></a>
                    <div class="text p-4">
                        <h3>Tomatoe Pie</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
                        <p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex ftco-animate">
                <div class="services-wrap d-flex">
                    <a href="#" class="img order-lg-last" style="background-image: url(../themes/pizza-gh/web/images/pizza-6.jpg);"></a>
                    <div class="text p-4">
                        <h3>Margherita</h3>
                        <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia</p>
                        <p class="price"><span>$2.90</span> <a href="#" class="ml-2 btn btn-white btn-outline-white">Order</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    <div>
        <!--        <div class="row justify-content-center mb-5 pb-3 mt-5 pt-5">
                    <div class="col-md-7 heading-section text-center ftco-animate">
                        <h2 class="mb-4">Our Menu Pricing</h2>
                        <p class="flip"><span class="deg1"></span><span class="deg2"></span><span class="deg3"></span></p>
                        <p class="mt-5">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
                    </div>
                </div>-->
        <!--        <div class="row justify-content-center mb-5 pb-3 mt-5 pt-5">
                    <div class="col-md-7 heading-section text-center ftco-animate">
                        <h2 class="mb-4">Pizza 32cm</h2>
                        <p class="flip"><span class="deg1"></span><span class="deg2"></span><span class="deg3"></span></p>
                    </div>
                </div>-->
        <?php
        foreach ($categorii as $categorie) {
            $parinte = \backend\models\Categorii::findOne(['id' => $categorie->parinte]);
            $nume=$categorie->nume;
            if(!is_null($parinte)){
                $nume= $parinte->nume . ' ' . $categorie->nume;
            }
            ?>
            <div class="row justify-content-center mb-5 pb-3 mt-5 pt-5">
                <div class="col-md-7 heading-section text-center ftco-animate">
                    <?php 
                        echo "<!-- Categoria  $nume-->";
                    ?>
                    <h2 class="mb-4"><?= $nume ?></h2>
                    <p class="flip"><span class="deg1"></span><span class="deg2"></span><span class="deg3"></span></p>
                </div>
            </div>
            <div class="row">
                <?php
                echo $this->render('_produse_meniu_view', ['idCategorie' => $categorie->id]);
                ?>
            </div>
            <?php
        }
        ?>


<!--        <div class="row">
            <div class="col-md-6">
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-1.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Italian Pizza</span></h3>
                            <span class="price">$20.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-2.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Hawaiian Pizza</span></h3>
                            <span class="price">$29.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-3.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Greek Pizza</span></h3>
                            <span class="price">$20.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-4.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Bacon Crispy Thins</span></h3>
                            <span class="price">$20.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-5.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Hawaiian Special</span></h3>
                            <span class="price">$49.91</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-6.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Ultimate Overload</span></h3>
                            <span class="price">$20.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-7.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Bacon Pizza</span></h3>
                            <span class="price">$20.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
                <div class="pricing-entry d-flex ftco-animate">
                    <div class="img" style="background-image: url(../themes/pizza-gh/web/images/pizza-8.jpg);"></div>
                    <div class="desc pl-3">
                        <div class="d-flex text align-items-center">
                            <h3><span>Ham &amp; Pineapple</span></h3>
                            <span class="price">$20.00</span>
                        </div>
                        <div class="d-block">
                            <p>A small river named Duden flows by their place and supplies</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
</section>
