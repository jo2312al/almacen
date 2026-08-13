<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */

$this->title = $model->arc_id;
$this->params['breadcrumbs'][] = ['label' => 'Archivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="archivo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Descargar', ['download', 'id' => $model->arc_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Localizar', ['/busqueda/localizar', 'arc_id' => $model->arc_id], ['class' => 'btn btn-info']) ?>
        <?php if ($model->arcCaja): ?>
            <?= Html::a('Vista QR', ['/caja/consulta', 'caj_id' => $model->arcCaja->caj_id], ['class' => 'btn btn-secondary']) ?>
        <?php endif; ?>
        <?php if ($model->arcAlumno): ?>
            <?= Html::a('Reporte Alumno', ['/reporte/alumno', 'id' => $model->arcAlumno->alu_id], ['class' => 'btn btn-secondary']) ?>
        <?php endif; ?>
        <?= Html::a('Actualizar', ['update', 'arc_id' => $model->arc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'arc_id' => $model->arc_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de eliminar este registro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'arc_id',
            'arc_codigo',
            'arc_nombre_documento',
            'arc_caja_id',
            'arc_alumno_id',
            'arc_ruta',
            'arc_fondo_id',
            'arc_clave_programatica_id',
            'arc_area_generadora_id',
            'arc_seccion_serie_id',
        ],
    ]) ?>

</div>
