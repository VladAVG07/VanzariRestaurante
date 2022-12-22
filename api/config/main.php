<?php
 
$params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php'), require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);
 
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ro',
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'   // here is our v1 modules
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
 
                $response = $event->sender;
                $message = $response->statusText;
                $success = $response->isSuccessful;
                $data = $success ? $response->data : null;
                //var_dump($response->statusCode);
                if ($response->statusCode == 422) {
                    $message = $response->data[0]['message'];
                    /*   $response->data = [
                      'success' => $success,
                      'data' => $data,
                      'message' => $message,
                      ]; */
                } else if ($response->statusCode == 401) {
                    $response->data = [
                        'success' => $success,
                        'data' => $data,
                        'message' => $response->statusText,
                    ];
                } /* else { */
                $response->statusCode = 200;
                $response->data = [
                    'success' => $success,
                    'data' => $data,
                    'message' => $message,
                        //'statusCode'=>$response->statusCode,
                ];
                //}
            }
        ,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'request' => [
            // Set Parser to JsonParser to accept Json in request
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'urlBackend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => 'backend/web/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/sala', // our country api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                    //'{numar}' => '<numar:.*>',
                    //'{institutie}' => '<institutie:\\w+>',
                    ],
                    'extraPatterns' => [
                    //'GET save-dosar/{numar}/{institutie}' => 'save-dosar',
                    //'GET ddd' => 'dosare', // 'xxxxx' refers to 'actionXxxxx'
                    ],
                    'except' => ['delete', 'create', 'update']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/recenzie', // our country api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                    //'{numar}' => '<numar:.*>',
                    //'{institutie}' => '<institutie:\\w+>',
                    ],
                    'extraPatterns' => [
                    //'GET save-dosar/{numar}/{institutie}' => 'save-dosar',
                    //'GET ddd' => 'dosare', // 'xxxxx' refers to 'actionXxxxx'
                    ],
                // 'except'=>['delete','create','update']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/device', // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                // 'except' => ['delete', 'index'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/user', // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{user}' => '<user:>',
                        '{pass}' => '<pass:>',
                        '{email}' => '<email:>',
                        '{tokens}' => '<tokens:>',
                        '{token}' => '<token:[a-zA-Z0-9\\-_]{32}>',
                    ],
                    'except' => ['update', 'view', 'delete'],
                    'extraPatterns' => [
                        //      'GET block-user/{id}'=>'block-user',
                        //    'GET unblock-user/{id}'=>'unblock-user',
                        //  'POST change-password/{token}' => 'change-password',
                        'POST edit' => 'edit',
                        'DELETE delete-disponibilitate/{id}' => 'delete-disponibilitate',
                        'POST add-disponibilitate' => 'add-disponibilitate',
                        'POST add-rezervare' => 'add-rezervare',
                        'POST edit-disponibilitate/{id}' => 'edit-disponibilitate',
                        'GET login/{user}/{pass}' => 'login',
                        'GET unsubscribe-push/{id}' => 'unsubscribe-push',
                        'GET subscribe-push' => 'subscribe-push',
                    //'GET check-email/{email}' => 'check-email',
                    //'GET check-username/{user}' => 'check-username',
                    //'GET reset-password/{email}' => 'reset-password',
                    // 'GET myads' => 'myadvertiserads', // 'xxxxx' refers to 'actionXxxxx'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/stadiu', // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'create', 'update'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/message', // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        'GET test' => 'test',
                    ],
                    'except' => ['delete'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/categorie-caz', // our country api rule,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'update'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/raport-detalii', // our country api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                    'except' => ['delete', 'update'],
                ],
            ],
        ]
    ],
    'params' => $params,
];
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

