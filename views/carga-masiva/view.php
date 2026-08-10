<?php

use app\models\CargaMasivaDetalle;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\CargaMasiva $model */
/** @var yii\data\ActiveDataProvider $detailsProvider */

$this->title = 'Carga #' . $model->car_id;
$this->params['breadcrumbs'][] = ['label' => 'Cargas Masivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="carga-masiva-view">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Resultado del procesamiento de la caja <?= Html::encode($model->caja ? $model->caja->caj_codigo : '') ?>.</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-arrow-left me-1"></i>Historial', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::a('<i class="bi bi-plus-lg me-1"></i>Nueva Carga', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total</div><div class="display-6 fw-bold"><?= Html::encode($model->car_total) ?></div></div></div></div>
        <div class="col-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Guardados</div><div class="display-6 fw-bold text-success"><?= Html::encode($model->car_exitosos) ?></div></div></div></div>
        <div class="col-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Pendientes</div><div class="display-6 fw-bold text-warning"><?= Html::encode($model->car_pendientes) ?></div></div></div></div>
        <div class="col-6 col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Errores</div><div class="display-6 fw-bold text-danger"><?= Html::encode($model->car_errores) ?></div></div></div></div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white"><strong>Datos del lote</strong></div>
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'car_id',
                    [
                        'attribute' => 'car_caja_id',
                        'value' => $model->caja ? $model->caja->caj_codigo : '(sin caja)',
                    ],
                    'car_estado',
                    'car_creado_en',
                    'car_finalizado_en',
                ],
            ]) ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white"><strong>Resultado por archivo</strong></div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $detailsProvider,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
                'columns' => [
                    'det_nombre_original',
                    'det_matricula_detectada',
                    [
                        'attribute' => 'det_estado',
                        'format' => 'raw',
                        'value' => function ($detail) {
                            $class = 'danger';
                            if ($detail->det_estado === CargaMasivaDetalle::ESTADO_GUARDADO) {
                                $class = 'success';
                            } elseif ($detail->det_estado === CargaMasivaDetalle::ESTADO_PENDIENTE) {
                                $class = 'warning text-dark';
                            }
                            return '<span class="badge bg-' . $class . '">' . Html::encode($detail->det_estado) . '</span>';
                        },
                    ],
                    [
                        'attribute' => 'det_alumno_id',
                        'value' => fn($detail) => $detail->alumno ? $detail->alumno->alu_matricula . ' - ' . $detail->alumno->alu_nombre : '',
                    ],
                    [
                        'attribute' => 'det_archivo_id',
                        'format' => 'raw',
                        'value' => fn($detail) => $detail->archivo
                            ? Html::a('Ver archivo', ['/archivo/view', 'arc_id' => $detail->archivo->arc_id], ['class' => 'btn btn-sm btn-outline-primary'])
                            : '',
                    ],
                    [
                        'label' => 'Acción',
                        'format' => 'raw',
                        'value' => fn($detail) => $detail->det_estado === CargaMasivaDetalle::ESTADO_PENDIENTE
                            ? Html::a('<i class="bi bi-person-plus me-1"></i>Revisar alumno', ['revisar', 'id' => $detail->det_id], ['class' => 'btn btn-sm btn-warning'])
                            : '',
                    ],
                    'det_mensaje:ntext',
                ],
            ]); ?>
        </div>
    </div>
</div>
