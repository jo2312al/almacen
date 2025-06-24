<?php

use app\models\Archivo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ArchivoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Archivos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archivo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Archivo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'arc_id',
            'arc_codigo',
            'arc_nombre_documento',
            'arc_caja_id',
            'arc_alumno_id',
            //'arc_ruta',
            //'arc_fondo_id',
            //'arc_clave_programatica_id',
            //'arc_area_generadora_id',
            //'arc_seccion_serie_id',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Archivo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'arc_id' => $model->arc_id]);
                 }
            ],
        ],
    ]); ?>


</div>
