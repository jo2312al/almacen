<?php use yii\helpers\Html; ?>
<div class="viewer-home">
 <div class="page-heading"><h1>Consulta documental</h1><p>Busca por matrícula, alumno, documento, caja o código clasificador.</p></div>
 <div class="card"><div class="card-body p-4"><?= Html::beginForm(['/busqueda/index'],'get',['class'=>'row g-2']) ?><div class="col-md-10"><?= Html::textInput('q','',['class'=>'form-control form-control-lg','placeholder'=>'Matrícula, nombre, documento o caja','aria-label'=>'Buscar documentos']) ?></div><div class="col-md-2 d-grid"><?= Html::submitButton('Buscar',['class'=>'btn btn-primary btn-lg']) ?></div><?= Html::endForm() ?></div></div>
 <div class="mt-3"><?= Html::a('<i class="bi bi-qr-code-scan me-2"></i>Consultar mediante QR',['/site/scan'],['class'=>'btn btn-outline-primary']) ?></div>
</div>
