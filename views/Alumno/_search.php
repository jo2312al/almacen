<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AlumnoSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="alumno-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'alu_id') ?>

    <?= $form->field($model, 'alu_matricula') ?>

    <?= $form->field($model, 'alu_nombre') ?>

    <?= $form->field($model, 'alu_paterno') ?>

    <?= $form->field($model, 'alu_materno') ?>

    <?php // echo $form->field($model, 'alu_generacion_id') ?>

    <?php // echo $form->field($model, 'alu_ingreso') ?>

    <?php // echo $form->field($model, 'alu_servicio_id') ?>

    <?php // echo $form->field($model, 'alu_carrera_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
