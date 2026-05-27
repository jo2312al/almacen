<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Carrera;
use app\models\Servicio;

/** @var yii\web\View $this */
/** @var app\models\AlumnoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Listado de Alumnos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alumno-index">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary fw-bold"><?= Html::encode($this->title) ?></h1>
        <p class="mb-0">
            <?= Html::a('<i class="bi bi-plus-lg"></i> Registrar Nuevo Alumno', ['alumno/create'], ['class' => 'btn btn-success shadow-sm']) ?>
        </p>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        // Configuración visual de la tabla
        'tableOptions' => [
            'class' => 'table table-hover table-bordered shadow-sm bg-white rounded',
        ],

        // Resumen personalizado (Texto arriba de la tabla)
        'summary' => '<div class="alert alert-light border text-center mb-3 text-muted">
                        <small>Mostrando <b>{begin}-{end}</b> de <b>{totalCount}</b> alumnos registrados.</small>
                      </div>',

        // Configuración de la Paginación (Centrada y con Iconos)
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
            'options' => ['class' => 'pagination justify-content-center mt-4'],
            'firstPageLabel' => '<i class="bi bi-skip-backward-fill"></i> Inicio',
            'lastPageLabel'  => 'Fin <i class="bi bi-skip-forward-fill"></i>',
            'prevPageLabel'  => '<i class="bi bi-chevron-left"></i>',
            'nextPageLabel'  => '<i class="bi bi-chevron-right"></i>',
            'maxButtonCount' => 5,
            'listOptions' => ['class' => 'pagination mb-0'],
        ],

        // Hacemos que toda la fila sea clickeable
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'style' => 'cursor: pointer; transition: background-color 0.2s;',
                'onclick' => 'location.href="' . Url::to(['alumno/view', 'alu_id' => $model->alu_id]) . '"',
                'title' => 'Ver expediente de ' . $model->alu_nombre
            ];
        },
        
        // Columnas
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 1. Matrícula
            [
                'attribute' => 'alu_matricula',
                'header' => 'Matrícula',
                'headerOptions' => ['class' => 'bg-light text-center', 'style' => 'width: 120px;'],
                'contentOptions' => ['class' => 'text-center fw-bold text-primary'],
            ],

            // 2. Nombre Completo
            [
                'attribute' => 'alu_nombre', 
                'header' => 'Nombre del Alumno',
                'headerOptions' => ['class' => 'bg-light'],
                'value' => function ($model) {
                    return $model->alu_nombre . ' ' . $model->alu_paterno . ' ' . $model->alu_materno;
                }
            ],

            // 3. Carrera (Con Filtro Dropdown)
            [
                'attribute' => 'alu_carrera_id',
                'header' => 'Carrera',
                'headerOptions' => ['class' => 'bg-light'],
                'value' => function ($model) {
                    return $model->aluCarrera ? $model->aluCarrera->car_nombre : 'Sin Asignar';
                },
                'filter' => ArrayHelper::map(Carrera::find()->orderBy('car_nombre')->all(), 'car_id', 'car_nombre'),
            ],

            // 4. Año de Ingreso
            [
                'attribute' => 'alu_ingreso',
                'header' => 'Ingreso',
                'headerOptions' => ['class' => 'bg-light text-center', 'style' => 'width: 100px;'],
                'contentOptions' => ['class' => 'text-center'],
            ],

            // 5. Servicio (Con Filtro Dropdown Formateado)
            [
                'attribute' => 'alu_servicio_id',
                'header' => 'Servicio Social',
                'headerOptions' => ['class' => 'bg-light'],
                'value' => function ($model) {
                    if ($model->aluServicio) {
                        $periodo = ($model->aluServicio->ser_periodo_id == 1) ? 'Ene-Jul' : 'Jul-Dic';
                        return $model->aluServicio->ser_anio . ' (' . $periodo . ')';
                    }
                    return 'Pendiente';
                },
                'filter' => ArrayHelper::map(Servicio::find()->orderBy(['ser_anio' => SORT_DESC])->all(), 'ser_id', function($model){
                    return $model->ser_anio . ' - ' . ($model->ser_periodo_id == 1 ? 'Ene-Jul' : 'Jul-Dic');
                }),
            ],
        ],
    ]); ?>

</div>