<?php

//return [
//    'id' => 'app-common-tests',
//    'basePath' => dirname(__DIR__),
//    'components' => [
//        'user' => [
//            'class' => 'yii\web\User',
//            'identityClass' => 'common\models\User',
//        ],
//    ],
//];

$config = yii\helpers\ArrayHelper::merge(
                require(__DIR__ . '/main.php'), require(__DIR__ . '/main-local.php'), [
            'id' => 'app-common-tests',
            'components' => [
                // 'db' => [
                //   'dsn' => 'mysql:host=localhost;dbname=yii_app_test',
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost;dbname=tenis_test',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ],
                'user' => [
                    'class' => 'yii\web\User',
                    'identityClass' => 'common\models\User',
                ],
            ]
                ]
);
return $config;
