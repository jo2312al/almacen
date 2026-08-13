<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

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
        if (Yii::$app->user->isGuest) {
            return $this->render('portada');
        }

        return $this->render('index');
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

    public function actionReportes()
    {
        return $this->render('reportes');
    }

    public function actionCatalogos()
    {
        return $this->render('catalogos');
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
}