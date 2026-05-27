<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="archivo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'arc_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'arc_nombre_documento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'arc_caja_id')->textInput() ?>

    <?= $form->field($model, 'arc_alumno_id')->textInput() ?>

    <?= $form->field($model, 'arc_ruta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'arc_fondo_id')->textInput() ?>

    <?= $form->field($model, 'arc_clave_programatica_id')->textInput() ?>

    <?= $form->field($model, 'arc_area_generadora_id')->textInput() ?>

    <?= $form->field($model, 'arc_seccion_serie_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
