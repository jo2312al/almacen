<?php
use yii\helpers\Html;
use yii\grid\GridView;
$this->title='Documentos'; $this->params['breadcrumbs'][]=$this->title;
?>
<div class="d-flex justify-content-between align-items-start page-heading"><div><h1>Documentos</h1><p>Busca y consulta el acervo documental.</p></div><?php if(Yii::$app->user->can('archivo.crear')): ?><?= Html::a('Registrar documento',['create'],['class'=>'btn btn-primary']) ?><?php endif ?></div>
<?= GridView::widget(['dataProvider'=>$dataProvider,'filterModel'=>$searchModel,'tableOptions'=>['class'=>'table table-hover align-middle'],'emptyText'=>'No encontramos documentos. Prueba buscando por código, nombre, alumno o caja.','columns'=>[
 ['attribute'=>'arc_codigo','label'=>'Código / expediente'],
 ['attribute'=>'arc_nombre_documento','label'=>'Documento'],
 ['attribute'=>'arc_alumno_id','label'=>'Alumno','value'=>fn($m)=>$m->arcAlumno ? $m->arcAlumno->alu_matricula.' · '.$m->arcAlumno->getNombreCompleto() : 'Sin alumno'],
 ['attribute'=>'arc_caja_id','label'=>'Caja','value'=>fn($m)=>$m->arcCaja ? $m->arcCaja->caj_codigo : 'Sin caja'],
 ['label'=>'Acciones','format'=>'raw','value'=>function($m){$links=Html::a('Ver',['view','arc_id'=>$m->arc_id],['class'=>'btn btn-sm btn-outline-primary']); if(Yii::$app->user->can('archivo.localizar')){$links.=' '.Html::a('Localizar',['/busqueda/localizar','arc_id'=>$m->arc_id],['class'=>'btn btn-sm btn-outline-secondary']);} return $links;}],
]]) ?>
