<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Anaquel;
use app\models\Nivelalmacenamiento;

/** @var yii\web\View $this */
/** @var app\models\Caja $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="caja-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="row align-items-end">
        
        <div class="col-md-5">
            <?= $form->field($model, 'caj_anaquel_id')->dropDownList(
                ArrayHelper::map(Anaquel::find()->all(), 'ana_id', 'ana_nombre'),
                // Texto de ayuda en español
                ['prompt' => 'Seleccione un anaquel...'] 
            )->label('Anaquel') // Etiqueta del campo en español ?>
        </div>

        <div class="col-md-5">
            <?= $form->field($model, 'caj_nivel_id')->dropDownList(
                ArrayHelper::map(Nivelalmacenamiento::find()->all(), 'niv_id', 'niv_nombre'),
                // Texto de ayuda en español
                ['prompt' => 'Seleccione un nivel...'] 
            )->label('Nivel') // Etiqueta del campo en español ?>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <?php // Texto del botón en español ?>
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>