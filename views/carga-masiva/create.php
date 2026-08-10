<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CargaMasivaForm $model */
/** @var array $catalogs */

$this->title = 'Carga Masiva de Caja';
$this->params['breadcrumbs'][] = ['label' => 'Cargas Masivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.carga-panel {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
    padding: 18px;
    margin-bottom: 18px;
}
.carga-panel legend {
    float: none;
    width: auto;
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 12px;
}
.file-summary {
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    background: #f8fafc;
    padding: 14px;
    margin-top: 10px;
}
.file-summary.is-ready {
    border-color: #22c55e;
    background: #f0fdf4;
}
.file-summary.has-error {
    border-color: #ef4444;
    background: #fef2f2;
}
.file-list {
    margin: 10px 0 0;
    padding-left: 18px;
    color: #334155;
}
.file-list li {
    margin-bottom: 4px;
}
.process-state {
    display: none;
    margin-top: 16px;
}
.process-state.is-visible {
    display: block;
}
CSS);

$this->registerJs(<<<JS
const inputArchivos = document.querySelector('#cargamasivaform-files');
const resumenArchivos = document.querySelector('#file-summary');
const listaArchivos = document.querySelector('#file-list');
const textoResumen = document.querySelector('#file-summary-text');
const botonProcesar = document.querySelector('#btn-procesar-caja');
const formulario = document.querySelector('#carga-masiva-form');
const estadoProceso = document.querySelector('#process-state');
const MAX_ARCHIVOS = 20;

function actualizarResumenArchivos() {
    const archivos = Array.from(inputArchivos.files || []);
    listaArchivos.innerHTML = '';
    resumenArchivos.classList.remove('is-ready', 'has-error');

    if (archivos.length === 0) {
        textoResumen.textContent = 'Aún no has seleccionado PDFs.';
        botonProcesar.disabled = false;
        return;
    }

    const noPdf = archivos.filter((archivo) => !archivo.name.toLowerCase().endsWith('.pdf'));
    const excedeLimite = archivos.length > MAX_ARCHIVOS;

    archivos.forEach((archivo) => {
        const elemento = document.createElement('li');
        const pesoMb = (archivo.size / 1024 / 1024).toFixed(2);
        elemento.textContent = `${archivo.name} (${pesoMb} MB)`;
        listaArchivos.appendChild(elemento);
    });

    if (noPdf.length > 0 || excedeLimite) {
        const partes = [];
        if (noPdf.length > 0) partes.push('solo se permiten archivos PDF');
        if (excedeLimite) partes.push(`máximo ${MAX_ARCHIVOS} archivos por lote`);
        textoResumen.textContent = `Revisa la selección: ${partes.join(' y ')}.`;
        resumenArchivos.classList.add('has-error');
        botonProcesar.disabled = true;
        return;
    }

    textoResumen.textContent = `${archivos.length} PDF(s) listos para procesar.`;
    resumenArchivos.classList.add('is-ready');
    botonProcesar.disabled = false;
}

if (inputArchivos) {
    inputArchivos.addEventListener('change', actualizarResumenArchivos);
}

if (formulario) {
    formulario.addEventListener('submit', function () {
        botonProcesar.disabled = true;
        botonProcesar.innerHTML = '<span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>Procesando caja...';
        estadoProceso.classList.add('is-visible');
    });
}
JS);
?>

<div class="carga-masiva-create">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Procesa varios PDFs de una misma caja y conserva trazabilidad del lote.</p>
        </div>
        <?= Html::a('<i class="bi bi-clock-history"></i> Historial', ['index'], ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php $form = ActiveForm::begin(['id' => 'carga-masiva-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <fieldset class="carga-panel">
        <legend>Caja y clasificación</legend>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'caja_id')->dropDownList(
                    ArrayHelper::map($catalogs['cajas'], 'caj_id', 'caj_codigo'),
                    ['prompt' => 'Seleccionar caja...']
                ) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'fondo_id')->dropDownList(
                    ArrayHelper::map($catalogs['fondos'], 'fon_id', fn($m) => $m->fon_codigo . ' - ' . $m->fon_descripcion),
                    ['prompt' => '00']
                ) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'clave_programatica_id')->dropDownList(
                    ArrayHelper::map($catalogs['claves'], 'cla_id', fn($m) => $m->cla_codigo . ' - ' . $m->cla_descripcion),
                    ['prompt' => '00']
                ) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'area_generadora_id')->dropDownList(
                    ArrayHelper::map($catalogs['areas'], 'are_id', fn($m) => $m->are_codigo . ' - ' . $m->are_descripcion),
                    ['prompt' => '00']
                ) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'seccion_serie_id')->dropDownList(
                    ArrayHelper::map($catalogs['secciones'], 'sec_id', fn($m) => $m->sec_codigo . ' - ' . $m->sec_descripcion),
                    ['prompt' => '00']
                ) ?>
            </div>
        </div>
    </fieldset>

    <fieldset class="carga-panel">
        <legend>PDFs de la caja</legend>
        <?= $form->field($model, 'files[]')->fileInput([
            'id' => 'cargamasivaform-files',
            'multiple' => true,
            'accept' => 'application/pdf,.pdf',
        ])->label('Selecciona hasta 20 PDFs') ?>

        <div id="file-summary" class="file-summary">
            <strong id="file-summary-text">Aún no has seleccionado PDFs.</strong>
            <ol id="file-list" class="file-list"></ol>
        </div>

        <div id="process-state" class="process-state alert alert-info mb-0">
            El lote se está procesando. Esta operación puede tardar según la cantidad de PDFs.
        </div>
    </fieldset>

    <div class="form-group text-center">
        <?= Html::submitButton('<i class="bi bi-cloud-arrow-up-fill me-2"></i>Procesar Caja', ['id' => 'btn-procesar-caja', 'class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>