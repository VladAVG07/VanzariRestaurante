<?php
namespace hail812\adminlte3\assets;

use yii\web\AssetBundle;

class AdminLteAsset extends AssetBundle
{
    public $sourcePath = '@frontend/themes/pizza-gh/web';

    public $css = [
        'css/owl.carousel.min.css'
    ];

    public $js = [
        'js/adminlte.min.js'
    ];

    // public $depends = [
    //     'hail812\adminlte3\assets\BaseAsset',
    //     'hail812\adminlte3\assets\PluginAsset'
    // ];
}