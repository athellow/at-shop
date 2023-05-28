<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'defaultRoute' => 'admin/index',
    'layout' => 'basic',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['auth/auth/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                    'logFile' => '@api/runtime/logs/app.' . date('Ymd') . '.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'html' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Html/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    // 'globals' => ['html' => '\yii\helpers\Html','Url' => '\yii\helpers\Url'],
                    'globals' => [
                        'html' => ['class' => '\yii\helpers\Html'],
                        'Url'  => ['class' => '\yii\helpers\Url']
                    ],
                    'uses' => ['yii\bootstrap'],
                ],
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
        'auth' => [
            'class' => 'backend\modules\auth\Module',
        ],
    ],
    'params' => $params,
];
