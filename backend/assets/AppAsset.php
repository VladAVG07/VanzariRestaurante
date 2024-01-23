<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
         'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
        'css/site.css',
        'css/index.css',
       
    ];
    public $js = [
        'js/index1.js',
        //  'https://code.jquery.com/jquery-3.6.0.min.js',
        'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
      
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
