<?php

namespace frontend\themes\pizzagh\assets;

use yii\web\AssetBundle;

class PizzGhAsset extends AssetBundle
{
    public $sourcePath = '@frontend/themes/pizzagh/web';

    public $css = [
        'css/open-iconic-bootstrap.min.css',
        'css/animate.css',
        'css/owl.carousel.min.css',
        'css/owl.theme.default.min.css',
        'css/magnific-popup.css',
        'css/aos.css',
        'css/ionicons.min.css',
        'css/bootstrap-datepicker.css',
        'css/jquery.timepicker.css',
        'css/flaticon.css',
        'css/icomoon.css',
        'css/style.css',
        'css/custom.css'
    ];

    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyD8yUzAKKN1bVaZD-kdrDVY8fskGWE_yuA&sensor=false',
       ///'js/jquery.min.js',
       'js/jquery-migrate-3.0.1.min.js',
       'js/popper.min.js',
       'js/bootstrap.min.js',
       'js/jquery.easing.1.3.js',
       'js/jquery.waypoints.min.js',
       'js/jquery.stellar.min.js',
       'js/owl.carousel.min.js',
       'js/jquery.magnific-popup.min.js',
       'js/aos.js',
       'js/jquery.animateNumber.min.js',
       'js/bootstrap-datepicker.js',
       'js/jquery.timepicker.min.js',
       'js/scrollax.min.js',
       'js/google-map.js',
       'js/main.js',
    ];

    public $depends = [
        'frontend\themes\pizzagh\assets\BaseAsset'
    ];
    public $publishOptions = [
        'forceCopy'=>true,
      ];
    // public $publishOptions = [
    //     "only" => [
    //         "css/",
    //         "images/",
    //         "fonts/"
    //     ],
    // ];

}
