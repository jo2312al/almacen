<?php
use app\components\RbacAccess;
use yii\helpers\Html;

$this->title = 'Catalogos documentales';
$this->params['breadcrumbs'][] = $this->title;
$groups = [
 'Organizacion documental' => [['Fondos','/fondo/index'],['Secciones y series','/seccion-serie/index'],['Areas generadoras','/area-generadora/index'],['Claves programaticas','/clave-programatica/index']],
 'Ubicacion fisica' => [['Anaqueles','/anaquel/index'],['Niveles de ubicacion','/nivelalmacenamiento/index']],
 'Datos academicos' => [['Carreras','/carrera/index'],['Generaciones','/generacion/index']],
];
?>
<div class="page-heading"><h1>Catalogos documentales</h1><p>Datos de referencia utilizados durante la captura y organizacion.</p></div>
<?php if (RbacAccess::can('catalogo.ver')): ?>
<div class="row g-3"><?php foreach($groups as $title=>$links): ?><div class="col-lg-4"><section class="card h-100"><div class="card-body"><h2 class="h5"><?= Html::encode($title) ?></h2><div class="list-group list-group-flush"><?php foreach($links as [$label,$url]): ?><?= Html::a(Html::encode($label), [$url], ['class'=>'list-group-item list-group-item-action px-0']) ?><?php endforeach ?></div></div></section></div><?php endforeach ?></div>
<?php endif; ?>