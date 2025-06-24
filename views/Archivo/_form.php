<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Caja;
use app\models\Fondo;
use app\models\ClaveProgramatica;
use app\models\AreaGeneradora;
use app\models\SeccionSerie;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<style>
    .form-control-smaller {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        height: 30px;
    }
    .form-group {
        margin-bottom: 0.5rem;
    }
    label {
        font-size: 0.9rem;
    }
    #matricula-extracted, #filename-generated {
        margin-top: 5px;
        font-weight: bold;
    }
</style>

<div class="archivo-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'archivo-form']]); ?>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="file">Archivo</label>
                <?= Html::fileInput('file', null, ['class' => 'form-control form-control-smaller', 'id' => 'file', 'onchange' => 'uploadFile()']) ?>
                <div id="matricula-extracted" style="display: none;">Matrícula extraída: <span id="matricula-value"></span></div>
                <div id="filename-generated" style="display: none;">Nombre generado: <span id="filename-value"></span></div>
            </div>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'arc_caja_id', ['labelOptions' => ['label' => 'Caja']])
                ->dropDownList(
                    ArrayHelper::map(Caja::find()->all(), 'caj_id', 'caj_codigo'),
                    ['prompt' => 'Seleccionar Caja', 'class' => 'form-control form-control-smaller', 'id' => 'arc_caja_id']
                ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'arc_fondo_id', ['labelOptions' => ['label' => 'Fondo']])
                ->dropDownList(
                    ArrayHelper::map(Fondo::find()->all(), 'fon_id', 'fon_descripcion'),
                    ['prompt' => 'Seleccionar Fondo', 'class' => 'form-control form-control-smaller', 'id' => 'arc_fondo_id']
                ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'arc_clave_programatica_id', ['labelOptions' => ['label' => 'Clave Programática']])
                ->dropDownList(
                    ArrayHelper::map(ClaveProgramatica::find()->all(), 'cla_id', 'cla_descripcion'),
                    ['prompt' => 'Seleccionar Clave', 'class' => 'form-control form-control-smaller', 'id' => 'arc_clave_programatica_id']
                ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'arc_area_generadora_id', ['labelOptions' => ['label' => 'Área Generadora']])
                ->dropDownList(
                    ArrayHelper::map(AreaGeneradora::find()->all(), 'are_id', 'are_descripcion'),
                    ['prompt' => 'Seleccionar Área', 'class' => 'form-control form-control-smaller', 'id' => 'arc_area_generadora_id']
                ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'arc_seccion_serie_id', ['labelOptions' => ['label' => 'Sección Serie']])
                ->dropDownList(
                    ArrayHelper::map(SeccionSerie::find()->all(), 'sec_id', 'sec_descripcion'),
                    ['prompt' => 'Seleccionar Sección', 'class' => 'form-control form-control-smaller', 'id' => 'arc_seccion_serie_id']
                ) ?>
        </div>
    </div>

    <!-- Campo oculto para el nombre del archivo generado -->
    <?= $form->field($model, 'arc_nombre_documento', ['template' => '{input}'])->hiddenInput(['id' => 'arc_nombre_documento']) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-sm']) ?>
        <?= Html::button('Subir archivo', ['class' => 'btn btn-primary btn-sm', 'id' => 'upload-button', 'onclick' => 'uploadFile()']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJsFile('@web/js/archivo-upload.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>