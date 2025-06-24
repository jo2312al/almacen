<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'es-ES', // Idioma de la aplicación

    // 1. RUTA POR DEFECTO: Asegura que la página de inicio sea site/index
    'defaultRoute' => 'site/index',
    
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/views',
                ],
            ],
        ],
        'request' => [
            'cookieValidationKey' => 'zGKvViRlv_fHAO2uZFmMgliGG-mbky_F',
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
        
        // 2. CONFIGURACIÓN DEL USUARIO (MODIFICADA)
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            
            // ¡IMPORTANTE! Esto le dice a Yii que cuando se requiera login,
            // debe ir a la página principal donde está nuestro modal.
            'loginUrl' => ['/site/index'],

            // Esta es la lógica que ya tenías para redirigir DESPUÉS de un login exitoso.
            // Funciona perfectamente con este nuevo enfoque.
            'on afterLogin' => function ($event) {
                $user = Yii::$app->user;
                if ($user->can('admin')) {
                    Yii::$app->response->redirect(['/site/index'])->send();
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
                'scheme' => 'smtp',
                'host' => 'smtp.gmail.com',
                'username' => 'jomejia00001@gmail.com',
                'password' => 'yamk sehh zwcg bnvz',
                'port' => '587',
                'encryption' => 'tls',
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
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                // Aquí puedes agregar reglas personalizadas si es necesario
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
    'modules' => [
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',

            // 3. MAPA DE CONTROLADOR (AÑADIDO)
            // Le dice a Webvimark que use nuestro controlador personalizado para la autenticación.
            

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
];

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
