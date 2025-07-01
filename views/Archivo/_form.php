<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Caja;
use app\models\Fondo;
use app\models\ClaveProgramatica;
use app\models\AreaGeneradora;
use app\models\SeccionSerie;
use app\models\Alumno;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="archivo-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'archivo-form']]); ?>

    <fieldset class="border p-3 mb-4 rounded">
        <legend class="w-auto px-2 h6" style="font-weight: 500;">Paso 1: Identificar Alumno</legend>
        <div class="row align-items-end">
            <div class="col-md-5">
                <?= $form->field($model, 'arc_alumno_id')->dropDownList(
                    ArrayHelper::map(Alumno::find()->all(), 'alu_id', 'nombreCompleto'),
                    ['prompt' => 'Seleccione un Alumno...']
                )->label('Alumno Existente') ?>
                <div id="alumno-feedback" class="form-text fw-bold"></div>
            </div>
            <div class="col-md-7">
                <?= $form->field($model, 'file')->fileInput(['class' => 'form-control'])->label('O Subir Constancia para Análisis') ?>
            </div>
        </div>
    </fieldset>

    <fieldset class="border p-3 mb-4 rounded">
        <legend class="w-auto px-2 h6" style="font-weight: 500;">Paso 2: Generar Código Clasificador</legend>
        <div class="row">
            <?php
                // --- INICIO DE LA CORRECCIÓN ---
                // Modificamos los dropdowns para que muestren "CODIGO - DESCRIPCION"
            ?>
            <div class="col-md-4"><?= $form->field($model, 'arc_fondo_id')->dropDownList(ArrayHelper::map(Fondo::find()->all(), 'fon_id', function($model) { return $model->fon_codigo . ' - ' . $model->fon_descripcion; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component'])->label('Fondo') ?></div>
            <div class="col-md-4"><?= $form->field($model, 'arc_clave_programatica_id')->dropDownList(ArrayHelper::map(ClaveProgramatica::find()->all(), 'cla_id', function($model) { return $model->cla_codigo . ' - ' . $model->cla_descripcion; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component'])->label('Clave Programática') ?></div>
            <div class="col-md-4"><?= $form->field($model, 'arc_area_generadora_id')->dropDownList(ArrayHelper::map(AreaGeneradora::find()->all(), 'are_id', function($model) { return $model->are_codigo . ' - ' . $model->are_descripcion; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component'])->label('Área Generadora') ?></div>
            <div class="col-md-6"><?= $form->field($model, 'arc_seccion_serie_id')->dropDownList(ArrayHelper::map(SeccionSerie::find()->all(), 'sec_id', function($model) { return $model->sec_codigo . ' - ' . $model->sec_descripcion; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component'])->label('Sección Serie') ?></div>
            <?php
                // --- FIN DE LA CORRECCIÓN ---
            ?>
            <div class="col-md-6"><?= $form->field($model, 'arc_caja_id')->dropDownList(ArrayHelper::map(Caja::find()->all(), 'caj_id', 'caj_codigo'), ['prompt' => 'Seleccionar...'])->label('Caja') ?></div>
            
            <div class="col-12 mt-3">
                <label class="form-label">Vista Previa del Código Clasificador</label>
                <input type="text" id="arc_codigo_preview" class="form-control" readonly>
            </div>
        </div>
    </fieldset>

    <?= $form->field($model, 'arc_codigo')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'arc_nombre_documento')->hiddenInput()->label(false) ?>

    <div class="form-group mt-4 text-center">
        <?= Html::submitButton('<i class="bi bi-save-fill me-2"></i>Guardar Archivo', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJsFile('@web/js/archivo-upload.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
