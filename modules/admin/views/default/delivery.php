<?php
use yii\helpers\Html;
$this->title = 'Administración del sistema';
$sections = [
 ['Usuarios y seguridad','Usuarios, roles y permisos del sistema.',[['Usuarios','/user-management/user/index'],['Roles','/user-management/role/index'],['Permisos','/user-management/permission/index']]],
 ['Auditoría','Actividad funcional y eventos disponibles.',[['Actividad documental','/bitacora/index']]],
];
?>
<div class="page-heading"><h1>Administración del sistema</h1><p>Gobierno técnico exclusivo de adminsuperior. La operación documental permanece fuera de esta área.</p></div>
<div class="row g-3"><?php foreach($sections as [$title,$text,$links]): ?><div class="col-lg-6"><section class="card h-100"><div class="card-body"><h2 class="h5"><?= Html::encode($title) ?></h2><p class="text-muted"><?= Html::encode($text) ?></p><?php foreach($links as [$label,$url]): ?><?= Html::a(Html::encode($label),[$url],['class'=>'btn btn-outline-primary me-2 mb-2']) ?><?php endforeach ?></div></section></div><?php endforeach ?></div>
<section class="card mt-3"><div class="card-body"><h2 class="h5">Funciones futuras recomendadas</h2><p class="mb-0 text-muted">Configuración general, integraciones OCR/API, estado de servicios, información de versión y mantenimiento aún no cuentan con pantallas funcionales verificadas. No se muestran como controles activos.</p></div></section>
