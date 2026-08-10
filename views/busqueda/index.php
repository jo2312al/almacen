<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $q */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Búsqueda Global';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="busqueda-index">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Localiza expedientes por matrícula, alumno, caja, documento o código clasificador.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= Html::beginForm(['index'], 'get', ['class' => 'row g-2 align-items-end']) ?>
                <div class="col-md-10">
                    <label class="form-label" for="global-search-q">Texto de búsqueda</label>
                    <?= Html::textInput('q', $q, [
                        'id' => 'global-search-q',
                        'class' => 'form-control form-control-lg',
                        'placeholder' => 'Ej. 21300878, José, AC01T0001, 00/00/...',
                    ]) ?>
                </div>
                <div class="col-md-2 d-grid">
                    <?= Html::submitButton('<i class="bi bi-search me-1"></i>Buscar', ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            <?= Html::endForm() ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Resultados</strong>
            <span class="text-muted small"><?= Html::encode($dataProvider->getTotalCount()) ?> registro(s)</span>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
                'columns' => [
                    [
                        'label' => 'Matrícula',
                        'value' => fn($model) => $model->arcAlumno ? $model->arcAlumno->alu_matricula : '',
                    ],
                    [
                        'label' => 'Alumno',
                        'value' => fn($model) => $model->arcAlumno ? $model->arcAlumno->getNombreCompleto() : '(sin alumno)',
                    ],
                    [
                        'label' => 'Caja',
                        'value' => fn($model) => $model->arcCaja ? $model->arcCaja->caj_codigo : '(sin caja)',
                    ],
                    'arc_codigo',
                    'arc_nombre_documento',
                    [
                        'label' => 'Acciones',
                        'format' => 'raw',
                        'value' => fn($model) => Html::a('Ver', ['/archivo/view', 'arc_id' => $model->arc_id], ['class' => 'btn btn-sm btn-outline-primary'])
                            . ' ' . Html::a('Localizar', ['/busqueda/localizar', 'arc_id' => $model->arc_id], ['class' => 'btn btn-sm btn-outline-info'])
                            . ' ' . Html::a('Descargar', ['/archivo/download', 'id' => $model->arc_id], ['class' => 'btn btn-sm btn-outline-success']),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
