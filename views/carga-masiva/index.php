<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cargas Masivas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="carga-masiva-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="bi bi-plus-lg me-1"></i>Nueva Carga', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'car_id',
            [
                'attribute' => 'car_caja_id',
                'value' => fn($model) => $model->caja ? $model->caja->caj_codigo : '(sin caja)',
            ],
            'car_estado',
            'car_total',
            'car_exitosos',
            'car_pendientes',
            'car_errores',
            'car_creado_en',
            [
                'class' => yii\grid\ActionColumn::class,
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>
