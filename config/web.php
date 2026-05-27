<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'es-ES', // Idioma de la aplicación

    // RUTA POR DEFECTO:
    'defaultRoute' => 'site/index',
    
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    
    // <-- MODIFICADO: Se registra el nuevo módulo 'admin'
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            // 'enableRegistration' => true,
            'on beforeAction' => function(yii\base\ActionEvent $event) {
                if ($event->action->uniqueId == 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                };
            },
        ],
        'pdfjs' => [
            'class' => \diecoding\pdfjs\Module::class,
        ],
    ],

    'components' => [
        'view' => [
            // El 'theme' ya no es necesario aquí, porque el layout se define
            // a nivel de módulo para el admin y a nivel de aplicación para el resto.
        ],
        'request' => [
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY') ?: 'change-me-in-local-config',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'qr' => [
            'class' => '\Da\QrCode\Component\QrCodeComponent',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        // <-- MODIFICADO: Lógica de redirección para el rol 'admin'
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            'loginUrl' => ['/site/index'],
            'on afterLogin' => function ($event) {
                $user = Yii::$app->user;
                if ($user->can('admin')) {
                    // Ahora redirige al dashboard de AdminLTE3
                    Yii::$app->response->redirect(['/admin'])->send();
                } elseif ($user->can('prueba')) {
                    Yii::$app->response->redirect(['/site/index-usuario'])->send();
                } elseif ($user->can('viewer')) {
                    Yii::$app->response->redirect(['/viewer/home'])->send();
                } else {
                    Yii::$app->response->redirect(['/site/index'])->send();
                }
                Yii::$app->end();
            },
        ],
        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
                'scheme' => getenv('SMTP_SCHEME') ?: 'smtp',
                'host' => getenv('SMTP_HOST') ?: 'smtp.gmail.com',
                'username' => getenv('SMTP_USERNAME') ?: '',
                'password' => getenv('SMTP_PASSWORD') ?: '',
                'port' => getenv('SMTP_PORT') ?: '587',
                'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
            ],
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
        'db' => $db,
        
        // <-- NUEVO: Reglas para que '/admin' funcione correctamente
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // Reglas para el módulo de administración
                'admin' => 'admin/default/index', // URL base /admin
                'admin/<controller:\w+>/<action:\w+>' => 'admin/<controller>/<action>',
                
                // Otras reglas que necesites para la parte pública
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],
    'on beforeRequest' => function () {
        if (Yii::$app->session->has('language')) {
            Yii::$app->language = Yii::$app->session->get('language');
        } else {
            Yii::$app->language = 'es-ES';
        }
    },
    'params' => $params,
];

$localConfig = __DIR__ . '/web.local.php';
if (is_file($localConfig)) {
    $config = \yii\helpers\ArrayHelper::merge($config, require $localConfig);
}

if (YII_ENV_DEV) {
    // Configuración para el entorno de desarrollo
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
