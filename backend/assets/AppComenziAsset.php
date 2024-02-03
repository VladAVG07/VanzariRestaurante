<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppComenziAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
         'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
        'css/site.css',
        'css/index.css',
        'css/css_comenzi.css',
       
    ];
    public $js = [
        'js/index1.js',
        'js/script_comenzi.js',
        'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
      
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}


