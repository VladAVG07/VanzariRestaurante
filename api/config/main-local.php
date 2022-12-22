<?php

$config = [
    'on beforeRequest' => function(){
        date_default_timezone_set('UTC');
    },
    'timezone' => 'UTC',
    'components' => [
        'request' => [
            'cookieValidationKey'=>'1OzuRkMUTQxlgTc5MKkDTsxhZDuyGAt7'
        ],
    ],
];
    
if (!YII_ENV_TEST){
    $config['bootstrap'][]='debug';
    $config['modules']['debug']='yii\debug\Module';
    $config['bootstrap'][]='gii';
    $config['modules']['gii']='yii\gii\Module';
}

return $config;