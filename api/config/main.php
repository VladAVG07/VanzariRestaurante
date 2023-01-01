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
            'class' => 'api\modules\v1\Module',   // here is our v1 modules
            'controllerNamespace' => 'api\modules\v1\controllers'

        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $message = $response->statusText;
                $messages = null;
                $success = $response->isSuccessful;
                $data = $success ? $response->data : null;
                if ($response->statusCode == 422) {
                    $message = $response->data[0]['message'];
                    $messages = $response->data;
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
                } else if ($response->statusCode == 404) {
                    $response->data = [
                        'success' => $success,
                        'data' => $data,
                        'message' => $response->statusText,
                    ];
                }

                /* else { */
                $message = is_array($response->data) && array_key_exists('message', $response->data) ? $response->data['message'] : $message;
                if (Yii::$app->request->get('suppress_response_code')) {

                    $code = $response->statusCode;
                    $response->statusCode = 200;
                    $response->data = [
                        'success' => $success,
                        'data' => $data,
                        'message' => $message,
                        'messages' => $messages,
                        'code' => $code,
                    ];
                } else {
                    $code = $response->statusCode;
                    $response->statusCode = 200;
                    $response->data = [
                        'success' => $success,
                        'data' => $data,
                        'messages' => $messages,
                        'message' => $message,
                        'code' => $code,
                    ];
                }
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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
//        'urlBackend' => [
//            'class' => 'yii\web\urlManager',
//            'baseUrl' => 'backend/web/',
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    //'class' => 'api\modules\v1\rules\CustomUrlRule',
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/categorii', // our country api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
                   // 'except' => ['view','index'],
                ],
                [
                    //'class' => 'api\modules\v1\rules\CustomUrlRule',
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/utilizatori', // our country api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{user}' => '<user:>',
                        '{pass}' => '<pass:>',
                    ],
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST changePassword' => 'change-password'
                    ],
                    'except' => ['view','index','delete'],
                ],
                [
                    // 'class' => 'api\modules\v1\rules\CustomUrlRule',
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/produse', // our country api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ],
//                    'except' => ['delete'],
                ],
            ],
        ]
    ],
    'params' => $params,
];
