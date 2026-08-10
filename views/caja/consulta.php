<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Caja $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Consulta de Caja ' . $model->caj_codigo;
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="caja-consulta">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Inventario digital consultable desde código QR.</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-arrow-left me-1"></i>Detalle interno', ['view', 'caj_id' => $model->caj_id], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::a('<i class="bi bi-download me-1"></i>Descargar QR', ['generar-qr', 'caj_id' => $model->caj_id], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Código de caja</div>
                    <div class="h3 mb-0"><?= Html::encode($model->caj_codigo) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Anaquel</div>
                    <div class="h5 mb-0"><?= Html::encode($model->cajAnaquel->ana_nombre ?? 'No asignado') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Nivel</div>
                    <div class="h5 mb-0"><?= Html::encode($model->cajNivel->niv_nombre ?? 'No asignado') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Documentos</div>
                    <div class="h3 mb-0"><?= Html::encode($dataProvider->getTotalCount()) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <strong>Contenido documental</strong>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
                'columns' => [
                    [
                        'label' => 'Matrícula',
                        'value' => fn($archivo) => $archivo->arcAlumno ? $archivo->arcAlumno->alu_matricula : '',
                    ],
                    [
                        'label' => 'Alumno',
                        'value' => fn($archivo) => $archivo->arcAlumno ? $archivo->arcAlumno->getNombreCompleto() : '(sin alumno)',
                    ],
                    'arc_codigo',
                    'arc_nombre_documento',
                    [
                        'label' => 'Acciones',
                        'format' => 'raw',
                        'value' => fn($archivo) => Html::a('Ver', ['/archivo/view', 'arc_id' => $archivo->arc_id], ['class' => 'btn btn-sm btn-outline-primary'])
                            . ' ' . Html::a('Descargar', ['/archivo/download', 'id' => $archivo->arc_id], ['class' => 'btn btn-sm btn-outline-success']),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
