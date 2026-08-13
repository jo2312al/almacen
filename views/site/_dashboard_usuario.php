<?php use yii\helpers\Html; ?>
<div class="page-heading"><h1>¿Qué deseas hacer?</h1><p>Selecciona una tarea para comenzar.</p></div>
<div class="row g-3 action-grid">
 <?php foreach ([['Registrar documento','Sube un PDF y revisa sus datos.','/archivo/create','bi-file-earmark-plus'],['Buscar documento','Busca por matrícula, nombre, código o caja.','/busqueda/index','bi-search'],['Escanear QR','Identifica y consulta una caja.','/site/scan','bi-qr-code-scan'],['Localizar archivo','Encuentra su caja, anaquel y nivel.','/busqueda/index','bi-geo-alt']] as [$title,$text,$url,$icon]): ?>
 <div class="col-md-6"><a class="task-link" href="<?= yii\helpers\Url::to([$url]) ?>"><i class="bi <?= $icon ?>"></i><span><strong><?= Html::encode($title) ?></strong><small><?= Html::encode($text) ?></small></span></a></div>
 <?php endforeach ?>
</div>
