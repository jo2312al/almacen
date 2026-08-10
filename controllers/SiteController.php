<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Alumno;
use app\models\Archivo;
use app\models\BitacoraAccion;
use app\models\Caja;
use app\models\CargaMasiva;
use app\models\CargaMasivaDetalle;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'metrics' => $this->dashboardMetrics(),
            'recentLoads' => CargaMasiva::find()->with('caja')->orderBy(['car_id' => SORT_DESC])->limit(5)->all(),
            'recentActions' => BitacoraAccion::find()->orderBy(['bit_id' => SORT_DESC])->limit(6)->all(),
        ]);
    }

    public function actionIndexUsuario()
    {
        return $this->render('index-usuario');
    }

    public function actionMenucrear()
    {
        return $this->render('menucrear');
    }

    public function actionMenubuscar()
    {
        return $this->render('menubuscar');
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionScan()
    {
        return $this->render('scan');
    }

    private function dashboardMetrics()
    {
        return [
            'alumnos' => Alumno::find()->count(),
            'cajas' => Caja::find()->count(),
            'archivos' => Archivo::find()->count(),
            'cargas' => CargaMasiva::find()->count(),
            'pendientes' => CargaMasivaDetalle::find()->where(['det_estado' => CargaMasivaDetalle::ESTADO_PENDIENTE])->count(),
            'errores' => CargaMasivaDetalle::find()->where(['det_estado' => CargaMasivaDetalle::ESTADO_ERROR])->count(),
        ];
    }
}
