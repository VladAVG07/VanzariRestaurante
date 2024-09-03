<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'stripe' => [
            'class' => 'backend\components\StripeComponent',
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'formatter' => [
            'defaultTimeZone' => 'UTC',
            'timeZone' => 'Europe/Bucharest',
            'dateFormat' => 'php:d-m-Y',
            'datetimeFormat' => 'php:d-m-Y H:i:s'
        ]
    ],
];
