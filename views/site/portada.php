<?php
use yii\helpers\Html;
$this->title = 'ASSRP · Gestión documental';
$this->registerCssFile('@web/css/landing.css', ['depends' => [app\assets\AppAsset::class]]);
?>
<div class="landing-page">
 <section class="landing-hero">
  <div class="landing-copy"><span class="landing-kicker">Instituto Tecnológico de Villahermosa</span><h1>Archivo documental,<br>claro y localizable.</h1><p>Registra, consulta y ubica expedientes institucionales desde un entorno seguro, organizado y fácil de utilizar.</p><div class="d-flex flex-wrap gap-2"><button type="button" class="btn btn-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#loginModal">Iniciar sesión</button><a class="btn btn-outline-primary btn-lg px-4" href="#funciones">Conocer el sistema</a></div><p class="landing-security"><i class="bi bi-shield-check"></i> Acceso protegido según el rol y los permisos de cada usuario.</p></div>
  <div class="landing-visual" aria-hidden="true"><div class="archive-illustration"><div class="archive-label">ASSRP</div><div class="archive-shelf"><span></span><span></span><span></span></div><div class="archive-document"><i class="bi bi-file-earmark-text"></i><span>Expediente</span></div><div class="archive-pin"><i class="bi bi-geo-alt-fill"></i></div></div></div>
 </section>
 <section id="funciones" class="landing-features"><div class="section-heading"><span>Gestión documental institucional</span><h2>Todo lo necesario para trabajar con el archivo</h2></div><div class="row g-3">
 <?php foreach ([['bi-file-earmark-plus','Registro documental','Carga documentos y confirma la información extraída automáticamente.'],['bi-search','Búsqueda unificada','Encuentra expedientes por matrícula, alumno, documento, código o caja.'],['bi-box-seam','Archivo físico','Consulta la caja, el anaquel y el nivel donde se encuentra cada documento.'],['bi-qr-code-scan','Consulta mediante QR','Identifica cajas rápidamente sin exponer información confidencial.']] as [$icon,$title,$text]): ?><div class="col-sm-6 col-lg-3"><article class="feature-item"><i class="bi <?= Html::encode($icon) ?>"></i><h3><?= Html::encode($title) ?></h3><p><?= Html::encode($text) ?></p></article></div><?php endforeach ?>
 </div></section>
 <section class="landing-access"><div><span>Usuarios autorizados</span><h2>Accede a tu espacio de trabajo</h2><p>Cada perfil muestra únicamente las funciones necesarias para su responsabilidad.</p></div><button type="button" class="btn btn-light btn-lg px-4" data-bs-toggle="modal" data-bs-target="#loginModal">Entrar al sistema</button></section>
</div>
