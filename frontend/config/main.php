<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'name' => 'Tìm Sân Online',
    'language' => 'vi',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
            'idParam' => '__id-user',
            'authTimeoutParam' => '__expire-user',
            'identityCookie' => ['name' => '_identity-user-frontend'],
        ],
        'owner' => [
            'class'=>'yii\web\User',
            'identityClass' => 'common\models\Owner',
            'enableAutoLogin' => true,
            'loginUrl' => ['owner/login'],
            'idParam' => '__id-owner',
            'authTimeoutParam' => '__expire-owner',
            'identityCookie' => ['name' => '_identity-owner-frontend'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],       
    ],
    'params' => $params,
];
