<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $proveedorDatos */

$this->title = 'Reporte por Alumno';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.reporte-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
}
.reporte-tabla {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
}
@media (max-width: 768px) {
    .reporte-toolbar { align-items: flex-start; flex-direction: column; }
}
CSS);
?>

<div class="reporte-alumnos">
    <div class="reporte-toolbar">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Consulta expedientes registrados por alumno y genera una ficha exportable.</p>
        </div>
        <?= Html::a('<i class="bi bi-box-seam"></i> Reporte de Cajas', ['cajas'], ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <div class="reporte-tabla">
        <?= GridView::widget([
            'dataProvider' => $proveedorDatos,
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
            'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
            'columns' => [
                'alu_matricula',
                [
                    'label' => 'Alumno',
                    'value' => fn($alumno) => $alumno->getNombreCompleto(),
                ],
                [
                    'label' => 'Carrera',
                    'value' => fn($alumno) => $alumno->aluCarrera ? $alumno->aluCarrera->car_nombre : 'Sin carrera',
                ],
                [
                    'label' => 'Expedientes',
                    'value' => fn($alumno) => count($alumno->archivos),
                ],
                [
                    'label' => 'Acciones',
                    'format' => 'raw',
                    'value' => fn($alumno) => Html::a('Ver reporte', ['alumno', 'id' => $alumno->alu_id], ['class' => 'btn btn-sm btn-outline-primary'])
                        . ' ' . Html::a('Exportar CSV', ['exportar-alumno', 'id' => $alumno->alu_id], ['class' => 'btn btn-sm btn-outline-success']),
                ],
            ],
        ]) ?>
    </div>
</div>