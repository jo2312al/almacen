<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Anaquel $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="anaquel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ana_nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
