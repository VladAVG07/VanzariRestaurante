<?php
return yii\helpers\ArrayHelper::merge(
    //require __DIR__ . '/../../common/config/test-local.php',
    require __DIR__ . '/main.php',
    require __DIR__ . '/main-local.php',
    require __DIR__ . '/test.php',
     [
        'components' => [
            'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost;dbname=tenis_test',
                    'username' => 'root',
                    'password' => '',
                    'charset' => 'utf8',
                ],
        ],
    ]
);
