<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cargas Masivas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="carga-masiva-index">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Historial de procesamiento por caja, con guardados, pendientes y errores.</p>
        </div>
        <?= Html::a('<i class="bi bi-plus-lg me-1"></i>Nueva Carga', ['create'], ['class' => 'btn btn-success btn-lg']) ?>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
                'columns' => [
                    [
                        'attribute' => 'car_id',
                        'label' => 'Lote',
                        'value' => fn($model) => '#' . $model->car_id,
                    ],
                    [
                        'attribute' => 'car_caja_id',
                        'value' => fn($model) => $model->caja ? $model->caja->caj_codigo : '(sin caja)',
                    ],
                    [
                        'attribute' => 'car_estado',
                        'format' => 'raw',
                        'value' => fn($model) => '<span class="badge bg-success">' . Html::encode($model->car_estado) . '</span>',
                    ],
                    'car_total',
                    [
                        'attribute' => 'car_exitosos',
                        'format' => 'raw',
                        'value' => fn($model) => '<span class="badge bg-success">' . Html::encode($model->car_exitosos) . '</span>',
                    ],
                    [
                        'attribute' => 'car_pendientes',
                        'format' => 'raw',
                        'value' => fn($model) => '<span class="badge bg-warning text-dark">' . Html::encode($model->car_pendientes) . '</span>',
                    ],
                    [
                        'attribute' => 'car_errores',
                        'format' => 'raw',
                        'value' => fn($model) => '<span class="badge bg-danger">' . Html::encode($model->car_errores) . '</span>',
                    ],
                    'car_creado_en',
                    [
                        'class' => yii\grid\ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => fn($url, $model) => Html::a('<i class="bi bi-eye"></i>', ['view', 'id' => $model->car_id], ['class' => 'btn btn-sm btn-outline-primary', 'title' => 'Ver lote']),
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
