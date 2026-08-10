<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Alumno $alumno */
/** @var yii\data\ActiveDataProvider $proveedorArchivos */

$this->title = 'Ficha Documental - ' . $alumno->alu_matricula;
$this->params['breadcrumbs'][] = ['label' => 'Reporte por Alumno', 'url' => ['alumnos']];
$this->params['breadcrumbs'][] = $this->title;

$totalArchivos = $proveedorArchivos->getTotalCount();

$this->registerCss(<<<CSS
.ficha-hero,
.ficha-panel,
.ficha-tabla {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
}
.ficha-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 18px;
    padding: 22px;
    margin-bottom: 18px;
}
.ficha-hero h1 {
    font-size: 28px;
    margin: 0 0 6px;
}
.ficha-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: start;
    justify-content: flex-end;
    gap: 8px;
}
.ficha-resumen {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}
.ficha-panel {
    padding: 16px;
}
.ficha-panel span {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.ficha-panel strong {
    display: block;
    color: #0f172a;
    margin-top: 4px;
    overflow-wrap: anywhere;
}
.ficha-tabla {
    overflow: hidden;
}
@media print {
    .breadcrumb, .ficha-actions, .btn { display: none !important; }
    .ficha-hero, .ficha-panel, .ficha-tabla { box-shadow: none; }
}
@media (max-width: 768px) {
    .ficha-hero { grid-template-columns: 1fr; }
    .ficha-actions { justify-content: flex-start; }
}
CSS);
?>

<div class="reporte-alumno">
    <section class="ficha-hero">
        <div>
            <h1><?= Html::encode($alumno->getNombreCompleto()) ?></h1>
            <p class="text-muted mb-0">Matrícula <?= Html::encode($alumno->alu_matricula) ?> · <?= Html::encode($totalArchivos) ?> expediente(s)</p>
        </div>
        <div class="ficha-actions">
            <?= Html::a('<i class="bi bi-download"></i> Exportar CSV', ['exportar-alumno', 'id' => $alumno->alu_id], ['class' => 'btn btn-success']) ?>
            <button type="button" class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Imprimir</button>
        </div>
    </section>

    <div class="ficha-resumen">
        <div class="ficha-panel">
            <span>Carrera</span>
            <strong><?= Html::encode($alumno->aluCarrera ? $alumno->aluCarrera->car_nombre : 'Sin carrera') ?></strong>
        </div>
        <div class="ficha-panel">
            <span>Generación</span>
            <strong><?= Html::encode($alumno->aluGeneracion ? $alumno->aluGeneracion->gen_nombre : 'Sin generación') ?></strong>
        </div>
        <div class="ficha-panel">
            <span>Servicio</span>
            <strong><?= Html::encode($alumno->aluServicio ? $alumno->aluServicio->ser_anio : 'Sin servicio') ?></strong>
        </div>
        <div class="ficha-panel">
            <span>Año de ingreso</span>
            <strong><?= Html::encode($alumno->alu_ingreso ?: 'Sin registro') ?></strong>
        </div>
    </div>

    <div class="ficha-tabla">
        <?= GridView::widget([
            'dataProvider' => $proveedorArchivos,
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
            'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
            'emptyText' => 'Este alumno todavía no tiene expedientes registrados.',
            'columns' => [
                'arc_codigo',
                'arc_nombre_documento',
                [
                    'label' => 'Caja',
                    'value' => fn($archivo) => $archivo->arcCaja ? $archivo->arcCaja->caj_codigo : 'Sin caja',
                ],
                [
                    'label' => 'Anaquel',
                    'value' => fn($archivo) => $archivo->arcCaja && $archivo->arcCaja->cajAnaquel ? $archivo->arcCaja->cajAnaquel->ana_nombre : 'Sin anaquel',
                ],
                [
                    'label' => 'Nivel',
                    'value' => fn($archivo) => $archivo->arcCaja && $archivo->arcCaja->cajNivel ? $archivo->arcCaja->cajNivel->niv_nombre : 'Sin nivel',
                ],
                [
                    'label' => 'Acciones',
                    'format' => 'raw',
                    'value' => fn($archivo) => Html::a('Ver', ['/archivo/view', 'arc_id' => $archivo->arc_id], ['class' => 'btn btn-sm btn-outline-primary'])
                        . ' ' . Html::a('Localizar', ['/busqueda/localizar', 'arc_id' => $archivo->arc_id], ['class' => 'btn btn-sm btn-outline-info'])
                        . ' ' . Html::a('Descargar', ['/archivo/download', 'id' => $archivo->arc_id], ['class' => 'btn btn-sm btn-outline-success']),
                ],
            ],
        ]) ?>
    </div>
</div>