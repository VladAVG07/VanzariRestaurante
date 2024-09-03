<?php
namespace api\modules\v1;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\v1\controllers';
    public function init()
    {
       // date_default_timezone_set('UTC');
        parent::init();
        \Yii::$app->user->enableSession=false;
    }
}
