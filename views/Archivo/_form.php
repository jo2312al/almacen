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
        <div class="row align-items-center">
            <div class="col-md-5">
                <?= $form->field($model, 'arc_alumno_id')->dropDownList(
                    ArrayHelper::map(Alumno::find()->orderBy('alu_paterno, alu_materno, alu_nombre')->all(), 'alu_id', 'nombreCompleto'),
                    ['prompt' => 'Seleccione un Alumno existente...']
                )->label('Alumno Registrado') ?>
                <div id="alumno-feedback" class="form-text fw-bold"></div>
            </div>

            <div class="col-md-2 text-center">
                <p class="mb-0">ó</p>
            </div>

            <div class="col-md-5">
                <div class="mb-3">
                    <label class="form-label" for="archivo-file">Subir Constancia para Análisis</label>
                    <div class="input-group">
                        <?= Html::activeFileInput($model, 'file', ['class' => 'form-control', 'id' => 'archivo-file']) ?>
                        <button id="btn-analizar-pdf" class="btn btn-secondary" type="button" disabled>
                            <i class="bi bi-robot me-1"></i> Analizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="border p-3 mb-4 rounded">
        <legend class="w-auto px-2 h6" style="font-weight: 500;">Paso 2: Generar Código Clasificador</legend>
        <div class="row">
            <div class="col-md-4"><?= $form->field($model, 'arc_fondo_id')->dropDownList(ArrayHelper::map(Fondo::find()->all(), 'fon_id', function($m) { return "{$m->fon_codigo} - {$m->fon_descripcion}"; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component']) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'arc_clave_programatica_id')->dropDownList(ArrayHelper::map(ClaveProgramatica::find()->all(), 'cla_id', function($m) { return "{$m->cla_codigo} - {$m->cla_descripcion}"; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component']) ?></div>
            <div class="col-md-4"><?= $form->field($model, 'arc_area_generadora_id')->dropDownList(ArrayHelper::map(AreaGeneradora::find()->all(), 'are_id', function($m) { return "{$m->are_codigo} - {$m->are_descripcion}"; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component']) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'arc_seccion_serie_id')->dropDownList(ArrayHelper::map(SeccionSerie::find()->all(), 'sec_id', function($m) { return "{$m->sec_codigo} - {$m->sec_descripcion}"; }), ['prompt' => 'Seleccionar...', 'class' => 'form-control code-component']) ?></div>
            <div class="col-md-6"><?= $form->field($model, 'arc_caja_id')->dropDownList(ArrayHelper::map(Caja::find()->all(), 'caj_id', 'caj_codigo'), ['prompt' => 'Seleccionar...']) ?></div>
            
            <div class="col-12 mt-3">
                <label class="form-label">Vista Previa del Código Clasificador</label>
                <input type="text" id="arc_codigo_preview" class="form-control" readonly>
            </div>
        </div>
    </fieldset>

    <?= $form->field($model, 'arc_codigo')->hiddenInput(['id' => 'archivo-arc_codigo'])->label(false) ?>
    <?= $form->field($model, 'arc_nombre_documento')->hiddenInput(['id' => 'archivo-arc_nombre_documento'])->label(false) ?>

    <div class="form-group mt-4 text-center">
        <?= Html::submitButton('<i class="bi bi-save-fill me-2"></i>Guardar Archivo', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
// Este JS se registra en _form.php para asegurar que siempre se cargue
$this->registerJsFile('@web/js/archivo-upload.js', ['depends' => [\yii\web\JqueryAsset::class, \yii\bootstrap5\BootstrapAsset::class]]);
?>