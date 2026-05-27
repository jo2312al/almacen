<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ArchivoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="archivo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'arc_id') ?>

    <?= $form->field($model, 'arc_codigo') ?>

    <?= $form->field($model, 'arc_nombre_documento') ?>

    <?= $form->field($model, 'arc_caja_id') ?>

    <?= $form->field($model, 'arc_alumno_id') ?>

    <?php // echo $form->field($model, 'arc_ruta') ?>

    <?php // echo $form->field($model, 'arc_fondo_id') ?>

    <?php // echo $form->field($model, 'arc_clave_programatica_id') ?>

    <?php // echo $form->field($model, 'arc_area_generadora_id') ?>

    <?php // echo $form->field($model, 'arc_seccion_serie_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
