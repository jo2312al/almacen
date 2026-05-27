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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="bi bi-plus-lg me-1"></i>Nueva Carga', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'car_id',
            [
                'attribute' => 'car_caja_id',
                'value' => $model->caja ? $model->caja->caj_codigo : '(sin caja)',
            ],
            'car_estado',
            'car_total',
            'car_exitosos',
            'car_pendientes',
            'car_errores',
            'car_creado_en',
            'car_finalizado_en',
        ],
    ]) ?>

    <h2 class="h4 mt-4">Resultado</h2>
    <?= GridView::widget([
        'dataProvider' => $detailsProvider,
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
                        $class = 'warning';
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
                    ? Html::a('Ver archivo', ['/archivo/view', 'arc_id' => $detail->archivo->arc_id])
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
