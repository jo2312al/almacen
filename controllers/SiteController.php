<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionIndexUsuario()
    {
        return $this->render('index-usuario'); 
    }
    
    // =========================================================================
    // === INICIO DE LA CORRECCIÓN: Acciones añadidas ==========================
    // =========================================================================
    /**
     * Displays the "Crear" menu page.
     * @return string
     */
    public function actionMenucrear()
    {
        return $this->render('menucrear');
    }

    /**
     * Displays the "Buscar" menu page.
     * @return string
     */
    public function actionMenubuscar()
    {
        return $this->render('menubuscar');
    }
    // =========================================================================
    // === FIN DE LA CORRECCIÓN ================================================
    // =========================================================================


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Displays the scan page.
     *
     * @return string
     */
    public function actionScan()
    {
        return $this->render('scan'); 
    }
}
