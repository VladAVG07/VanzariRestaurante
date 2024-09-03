<?php

use frontend\themes\pizzagh\assets\PizzGhAsset;
use yii\bootstrap4\Modal;
use yii\helpers\Html;
//use kartik\touchspin\TouchSpin;
use yii\helpers\Url;

$this->title = 'Acasă - DioBistro Călărași';
$assetDir = Yii::$app->assetManager->getBundle('frontend\themes\pizzagh\assets\PizzGhAsset');
$urlCategorie = \yii\helpers\Url::toRoute('site/schimba-categorie');
$urlInchis = Url::toRoute('site/restaurant-inchis');

$comanda = Yii::$app->session->get('comanda');

if (!Yii::$app->session->has('comanda')) {
    $avemComanda = 0;
    echo "<script>console.log('Session comanda does not exist');</script>";
} else {
    $avemComanda = 1;
    echo "<script>console.log('Session comanda exists with value: ');</script>";
}

Yii::$app->session->remove('comanda');
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
<?php
$formatJs = <<< SCRIPT
let mesajInchis;
function isRestaurantClosed() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '$urlInchis', // URL of the API endpoint
            type: 'GET', // HTTP method
            success: function(data) {
                data1 = JSON.parse(data);
                mesajInchis = data1.message;
                resolve(data1.inchis === 1);
            },
        });
    });
}

async function showPopup() {
    try {
        const isClosed = await isRestaurantClosed();
        if (isClosed) {
            const popup = document.getElementById('closed-popup');
            $('#pMesaj').html(mesajInchis); // Corrected the jQuery selector
            popup.classList.add('show');
        }
    } catch (error) {
        console.error('Error in showPopup:', error);
    }
}

function hidePopup() {
    const popup = document.getElementById('closed-popup');
    popup.classList.remove('show');
}

document.getElementById('btnInchide').addEventListener('click', function() {
    const popup = document.getElementById('empty-popup');
    popup.classList.remove('show');
});

document.addEventListener('DOMContentLoaded', (event) => {
    if ('$avemComanda' == '1'){
    $('#comanda-modal').modal('show');
    }

    document.querySelector('.close-btn').addEventListener('click', hidePopup);
    showPopup();
    // Set interval to check every 5 minutes (300000 milliseconds)
    setInterval(showPopup, 300000); // Corrected the interval to 300000 milliseconds (5 minutes)
});
        
document.addEventListener("DOMContentLoaded", function() {
    var cookieNotification = document.getElementById("cookie-notification");
    var acceptButton = document.getElementById("accept-cookies");

    // Show the notification after the page loads
    setTimeout(function() {
        cookieNotification.classList.add("show");
    }, 1000); // Adjust the delay as needed

    // Hide the notification and set a cookie when the "Accept" button is clicked
    acceptButton.addEventListener("click", function() {
        cookieNotification.remove(); // Remove the notification from the DOM
        setCookie("cookiesAccepted", "true", 30); // Cookie expires in 30 days
    });

    // Hide the notification when the "Reject" button is clicked


    // Check if the cookie is already set
    if (getCookie("cookiesAccepted")) {
        cookieNotification.remove(); // Remove the notification from the DOM
    }
});

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
        
SCRIPT;
$this->registerJs($formatJs, yii\web\View::POS_END);
?>

<?php
    Modal::begin([
        'title' => '<h4>Comanda confirmata!</h4>',
        'id' => 'comanda-modal',
        'size' => 'modal-lg', // You can use 'lg', 'sm', or 'xl' for large, small, or extra-large modal
        'options' => [
            'class' => 'custom-modal-bg', // Add your custom CSS class here
        ],
    ]);
    ?>
    <?php

    if (!is_null($comanda)) {
        $numarComanda = $comanda->numar_comanda;

        ?>
        <div class="modal-content">
            <div class="modal-body">
                <h4>
                    <center>Comanda cu numarul <?= $numarComanda ?> a fost procesata si este in curs de pregatire! </center>
                </h4>
            </div>
        </div>
        <?php
    }
    Modal::end();
    ?>
<div id="closed-popup" class="popup">
    <div class="popup-content">
        <p id="pMesaj">test</p>
        <button class="close-btn">&times;</button>
    </div>
</div>

<div id="empty-popup" class="popup">
    <div class="popup-content">
        <p id="pMesaj">Cosul este gol! Va rugam sa adaugati produse.</p>
        <button class="close-btn" id="btnInchide">&times;</button>
    </div>
</div>

<div id="cookie-notification" class="cookie-notification">
    <div class="cookie-content">
        Acest site folosește cookie-uri pentru a stoca datele coșului de cumpărături și pentru a vă oferi cea mai bună experiență posibilă. Continuând să navigați pe site, sunteți de acord cu utilizarea cookie-urilor.
        <div>
            <button id="accept-cookies" class="cookie-button">Accept</button>
        </div>
    </div>
</div>

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
                            $restaurantId = 10;
                            $categorii = \backend\models\Categorii::find()
                                    ->innerJoin('produse p', 'p.categorie = categorii.id')
                                    ->innerJoin('restaurante_categorii', 'categorii.id = restaurante_categorii.categorie')
                                    ->where(['restaurante_categorii.restaurant' => $restaurantId])
                                    //->andWhere(['<>', 'categorii.parinte', 'null'])
                                    ->andWhere(['<>', 'categorii.nume', 'Servicii'])
                                    ->orderBy(['ordine' => SORT_ASC, 'nume' => SORT_ASC])
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
                                    'style' => 'margin-bottom:10px;',
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
                <div class="row no-gutters" style="justify-content: center;">
                    <div class="col-md-3 d-block ftco-animate" style="margin-right:32px;margin-left:32px;">
                        <div class="d-flex" style="margin-bottom:16px">
                            <div class="icon"><span class="icon-phone"></span></div>
                            <div class="text">
                                <h3>Telefon: +40722 885 551</h3>
                                <p>DIO Bistro este locul unde tu și prietenii tăi o să vă simțiți minunat!</p>
                            </div>
                        </div>
                        <div class="d-flex" style="margin-bottom:16px">
                            <div class="icon"><span class="icon-my_location"></span></div>
                            <div class="text">
                                <h3>Adresa: Prelungirea București numărul 123</h3>
                                <p>Călărași, România</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="icon"><span class="fas fa-truck"></span></div>
                            <?php
                            setlocale(LC_TIME, 'ro_RO');
                            $dayOfWeekNumber = intval(date('N'));
                            $model = \backend\models\IntervaleLivrare::findOne(['restaurant' => 10, 'ziua_saptamanii' => $dayOfWeekNumber]);
                            $interval = 'Nu se fac livrari';
                            if (!is_null($model)) {
                                $interval = $model->ora_inceput . ' - ' . $model->ora_sfarsit;
                            }
                            ?>
                            <div class="text">
                                <h3>Astazi livram intre orele: </h3>
                                <p><?= $interval ?> </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex ftco-animate" style="margin-right:32px;margin-left:32px;">
                        <div class="icon"><span class="icon-clock-o"></span></div>                 
                        <div>
                            <h3>Program lucru: </h3>
                            <?php
                            $romanianDays = array(
                                1 => 'Luni',
                                2 => 'Marti',
                                3 => 'Miercuri',
                                4 => 'Joi',
                                5 => 'Vineri',
                                6 => 'Sambata',
                                7 => 'Duminica'
                            );
                            for ($i = 1; $i <= 7; $i++) {
                                $model = \backend\models\IntervaleLivrare::findOne(['restaurant' => 10, 'ziua_saptamanii' => $i, 'program' => 0]);
                                $interval = 'Nu se fac livrari';
                                if (!is_null($model))
                                    $interval = $model->ora_inceput . ' - ' . $model->ora_sfarsit;
                                ?>

                                <h3><?= $romanianDays[$i] ?>: <span class="livrare"><?= $interval ?></span></h3>

                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex ftco-animate" style="margin-right:32px;margin-left:32px;">
                        <div class="icon"><span class="fas fa-truck"></span></div>
                        <div>
                            <h3>Program livrari: </h3>
                            <?php
                            for ($i = 1; $i <= 7; $i++) {
                                $model = \backend\models\IntervaleLivrare::findOne(['restaurant' => 10, 'ziua_saptamanii' => $i, 'program' => 1]);
                                $interval = 'Nu se fac livrari';
                                if (!is_null($model))
                                    $interval = $model->ora_inceput . ' - ' . $model->ora_sfarsit;
                                ?>

                                <h3><?= $romanianDays[$i] ?>: <span class="livrare"><?= $interval ?></span></h3>

                                <?php
                            }
                            ?>
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