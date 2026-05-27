<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\AreaGeneradora $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="area-generadora-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'are_codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'are_descripcion')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
