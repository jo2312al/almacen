<?php
use yii\helpers\Html;
$this->title = 'Reportes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-heading"><h1>Reportes</h1><p>Consulta y exporta información de la operación documental.</p></div>
<div class="row g-3">
  <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5">Inventario de cajas</h2><p class="text-muted">Ubicación y cantidad de documentos por caja.</p><?= Html::a('Abrir reporte', ['/reporte/cajas'], ['class'=>'btn btn-outline-primary']) ?></div></div></div>
  <div class="col-md-6"><div class="card h-100"><div class="card-body"><h2 class="h5">Expedientes por alumno</h2><p class="text-muted">Consulta documental organizada por matrícula.</p><?= Html::a('Abrir reporte', ['/reporte/alumnos'], ['class'=>'btn btn-outline-primary']) ?></div></div></div>
</div>
