<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $proveedorDatos */

$this->title = 'Reporte de Cajas';
$this->params['breadcrumbs'][] = $this->title;

$totalCajas = $proveedorDatos->getTotalCount();
$totalDocumentos = 0;
foreach ($proveedorDatos->query->all() as $cajaResumen) {
    $totalDocumentos += count($cajaResumen->archivos);
}

$this->registerCss(<<<CSS
.reporte-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
}
.reporte-metricas {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}
.reporte-metrica {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
}
.reporte-metrica span {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.reporte-metrica strong {
    display: block;
    color: #0f172a;
    font-size: 30px;
    margin-top: 4px;
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

<div class="reporte-cajas">
    <div class="reporte-toolbar">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Resumen operativo de cajas físicas, ubicación y documentos registrados.</p>
        </div>
        <?= Html::a('<i class="bi bi-download"></i> Exportar CSV', ['exportar-cajas'], ['class' => 'btn btn-success']) ?>
    </div>

    <div class="reporte-metricas">
        <div class="reporte-metrica">
            <span>Cajas registradas</span>
            <strong><?= Html::encode($totalCajas) ?></strong>
        </div>
        <div class="reporte-metrica">
            <span>Documentos asociados</span>
            <strong><?= Html::encode($totalDocumentos) ?></strong>
        </div>
    </div>

    <div class="reporte-tabla">
        <?= GridView::widget([
            'dataProvider' => $proveedorDatos,
            'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
            'summaryOptions' => ['class' => 'px-3 pt-3 text-muted small'],
            'columns' => [
                [
                    'label' => 'Caja',
                    'value' => fn($caja) => $caja->caj_codigo,
                ],
                [
                    'label' => 'Anaquel',
                    'value' => fn($caja) => $caja->cajAnaquel ? $caja->cajAnaquel->ana_nombre : 'Sin anaquel',
                ],
                [
                    'label' => 'Nivel',
                    'value' => fn($caja) => $caja->cajNivel ? $caja->cajNivel->niv_nombre : 'Sin nivel',
                ],
                [
                    'label' => 'Documentos',
                    'value' => fn($caja) => count($caja->archivos),
                ],
                [
                    'label' => 'Acciones',
                    'format' => 'raw',
                    'value' => fn($caja) => Html::a('Ver caja', ['/caja/view', 'caj_id' => $caja->caj_id], ['class' => 'btn btn-sm btn-outline-primary'])
                        . ' ' . Html::a('Vista QR', ['/caja/consulta', 'caj_id' => $caja->caj_id], ['class' => 'btn btn-sm btn-outline-success']),
                ],
            ],
        ]) ?>
    </div>
</div>