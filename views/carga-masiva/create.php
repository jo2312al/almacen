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
?>

<div class="carga-masiva-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <fieldset class="border p-3 mb-4 rounded">
        <legend class="w-auto px-2 h6">Caja y clasificación</legend>
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

    <fieldset class="border p-3 mb-4 rounded">
        <legend class="w-auto px-2 h6">PDFs</legend>
        <?= $form->field($model, 'files[]')->fileInput(['multiple' => true, 'accept' => 'application/pdf'])->label('Selecciona PDFs') ?>
    </fieldset>

    <div class="form-group text-center">
        <?= Html::submitButton('<i class="bi bi-cloud-arrow-up-fill me-2"></i>Procesar Caja', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
