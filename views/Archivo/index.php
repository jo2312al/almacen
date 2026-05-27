<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArchivoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Archivos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archivo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Archivo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'arc_codigo',
            'arc_nombre_documento',
            [
                'attribute' => 'arc_caja_id',
                'label' => 'Caja',
                'value' => function ($model) {
                    return $model->arcCaja ? $model->arcCaja->caj_id : 'No definido';
                },
            ],
            [
                'attribute' => 'arc_alumno_id',
                'label' => 'Alumno',
                'value' => function ($model) {
                    return $model->arcAlumno ? $model->arcAlumno->alu_id : 'No definido';
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>