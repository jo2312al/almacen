<?php
use yii\helpers\Html;
$this->title = 'Catálogos documentales';
$this->params['breadcrumbs'][] = $this->title;
$groups = [
 'Organización documental' => [['Fondos','/fondo/index'],['Secciones y series','/seccion-serie/index'],['Áreas generadoras','/area-generadora/index'],['Claves programáticas','/clave-programatica/index']],
 'Ubicación física' => [['Anaqueles','/anaquel/index'],['Niveles de ubicación','/nivelalmacenamiento/index']],
 'Datos académicos' => [['Carreras','/carrera/index'],['Generaciones','/generacion/index']],
];
?>
<div class="page-heading"><h1>Catálogos documentales</h1><p>Datos de referencia utilizados durante la captura y organización.</p></div>
<div class="row g-3"><?php foreach($groups as $title=>$links): ?><div class="col-lg-4"><section class="card h-100"><div class="card-body"><h2 class="h5"><?= Html::encode($title) ?></h2><div class="list-group list-group-flush"><?php foreach($links as [$label,$url]): ?><?= Html::a(Html::encode($label), [$url], ['class'=>'list-group-item list-group-item-action px-0']) ?><?php endforeach ?></div></div></section></div><?php endforeach ?></div>
