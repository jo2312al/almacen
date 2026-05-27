<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use webvimark\modules\UserManagement\models\forms\LoginForm as WebvimarkLoginForm;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\filters\VerbFilter;

/**
 * PublicController maneja acciones que deben ser accesibles para todos,
 * sin ser bloqueadas por filtros de seguridad como GhostAccessControl.
 */
class PublicController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'ajax-login' => ['post'], // Forzar método POST por seguridad
                ],
            ],
        ];
    }

    /**
     * Maneja el proceso de login desde el modal AJAX.
     * Este es el punto de entrada definitivo para nuestro formulario de login.
     */
    public function actionAjaxLogin()
    {
        // Nos aseguramos que sea una petición AJAX.
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        $model = new WebvimarkLoginForm();

        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->login()) {
                // Si el login es exitoso, determinamos la URL de redirección.
                $user = Yii::$app->user;
                $url = ['/site/index']; // URL por defecto

                if ($user->can('admin'))       $url = ['/site/index'];
                elseif ($user->can('prueba'))   $url = ['/site/index-usuario'];
                elseif ($user->can('viewer'))   $url = ['/viewer/home'];

                // Devolvemos una respuesta JSON con la URL.
                return ['success' => true, 'redirectUrl' => Url::to($url)];
            } else {
                // Si falla, devolvemos los errores de validación.
                return ActiveForm::validate($model);
            }
        }

        // Si no es una petición POST válida, no hacemos nada.
        return $this->goHome();
    }
}
