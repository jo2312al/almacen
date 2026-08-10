<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bitácora';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bitacora-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Registro operativo de acciones importantes del sistema.</p>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped table-hover align-middle'],
        'columns' => [
            'bit_creado_en',
            'bit_usuario',
            [
                'attribute' => 'bit_accion',
                'format' => 'raw',
                'value' => fn($model) => '<span class="badge bg-primary">' . Html::encode($model->bit_accion) . '</span>',
            ],
            'bit_entidad',
            'bit_entidad_id',
            'bit_descripcion:ntext',
            'bit_ip',
        ],
    ]); ?>
</div>
