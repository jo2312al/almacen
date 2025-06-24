<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SeccionSerie $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="seccion-serie-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sec_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sec_descripcion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
