<?php
namespace app\controllers;

use webvimark\modules\UserManagement\controllers\AuthController as WebvimarkAuthController;
use webvimark\modules\UserManagement\models\forms\LoginForm;
use Yii;
use yii\web\Response;
use yii\helpers\Url;

class CustomAuthController extends WebvimarkAuthController
{
    /**
     * Handles the login process.
     * This action is customized to handle AJAX requests from the login modal.
     */
    public function actionLogin()
    {
        // If the user is already logged in, redirect to home.
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        // Check if it's an AJAX request and the model can be loaded with POST data.
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            // Set the response format to JSON for AJAX handling.
            Yii::$app->response->format = Response::FORMAT_JSON;

            if ($model->login()) {
                // If login is successful, get the role-based redirect URL.
                $redirectUrl = $this->getRedirectUrl();
                // Return a success response with the redirect URL.
                return ['success' => true, 'redirectUrl' => $redirectUrl];
            } else {
                // If login fails, return validation errors.
                return $this->ajaxValidate($model);
            }
        }
        
        // For non-AJAX requests, render the login page (should not happen with our setup).
        return $this->renderIsAjax('login', compact('model'));
    }

    /**
     * Determines the redirect URL based on the user's role.
     * This method contains your original redirection logic from 'on afterLogin'.
     * @return string The URL to redirect to.
     */
    protected function getRedirectUrl()
    {
        $user = Yii::$app->user;
        $url = ['/site/index']; // Default URL

        if ($user->can('admin')) {
            $url = ['/site/index'];
        } elseif ($user->can('prueba')) {
            $url = ['/site/index-usuario'];
        } elseif ($user->can('viewer')) {
            $url = ['/viewer/home'];
        }

        return Url::to($url);
    }
}
