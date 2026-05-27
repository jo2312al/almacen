<?php
use webvimark\modules\UserManagement\models\forms\LoginForm as WebvimarkLoginForm;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$model = new WebvimarkLoginForm();
?>

<?php $form = ActiveForm::begin([
    'id'      => 'login-form-modal',
    // ¡IMPORTANTE! La acción ahora apunta a nuestra nueva ruta segura.
    'action'  => ['/public/ajax-login'],
    'enableAjaxValidation' => true,
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template' => "{input}\n{error}",
    ],
]); ?>

    <!-- Campo de Usuario -->
    <div class="mb-3">
        <?= $form->field($model, 'username')->textInput([
            'autofocus' => true,
            'class' => 'form-control form-control-lg',
            'placeholder' => $model->getAttributeLabel('username')
        ])->label(false) ?>
    </div>

    <!-- Campo de Contraseña -->
    <div class="mb-3">
        <?= $form->field($model, 'password')->passwordInput([
            'class' => 'form-control form-control-lg',
            'placeholder' => $model->getAttributeLabel('password')
        ])->label(false) ?>
    </div>

    <!-- Checkbox "Recordarme" -->
    <div class="mb-3">
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
    </div>

    <!-- Botón de Login (ancho completo) -->
    <div class="d-grid gap-2">
        <?= Html::submitButton(
            UserManagementModule::t('front', 'Login'),
            ['class' => 'btn btn-primary btn-lg']
        ) ?>
    </div>

<?php ActiveForm::end(); ?>
