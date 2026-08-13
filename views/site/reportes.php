<?php
use app\components\RbacAccess;
use yii\helpers\Html;

$this->title = 'Reportes';
$this->params['breadcrumbs'][] = $this->title;
$items = [
    ['Inventario de cajas', 'Ubicacion y cantidad de documentos por caja.', ['/reporte/cajas'], RbacAccess::can('reporte.ver')],
    ['Expedientes por alumno', 'Consulta documental organizada por matricula.', ['/reporte/alumnos'], RbacAccess::can('reporte.ver')],
];
$visibleItems = array_filter($items, static fn($item) => $item[3]);
?>
<div class="page-heading"><h1>Reportes</h1><p>Consulta y exporta informacion de la operacion documental.</p></div>
<div class="row g-3">
    <?php foreach ($visibleItems as [$title, $description, $url]): ?>
        <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5"><?= Html::encode($title) ?></h2><p class="text-muted"><?= Html::encode($description) ?></p><?= Html::a('Abrir reporte', $url, ['class'=>'btn btn-outline-primary']) ?></div></div></div>
    <?php endforeach; ?>
</div>