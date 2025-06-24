<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\Anaquel;
use app\models\Nivelalmacenamiento;

/** @var yii\web\View $this */
/** @var app\models\CajaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Cajas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caja-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Caja', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-hover',
        ],
        // Convierte cada fila en un enlace a la vista de esa caja
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'style' => 'cursor: pointer', // Muestra una mano al pasar el cursor
                'onclick' => 'location.href="' . Url::to(['view', 'caj_id' => $model->caj_id]) . '"',
            ];
        },
        // Traducción de los textos del GridView
        'summary' => 'Mostrando <b>{begin}-{end}</b> de <b>{totalCount}</b> cajas.',
        'emptyText' => 'No se encontraron resultados.',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => 'N°'],

            // Atributos del modelo con encabezados en español
            [
                'attribute' => 'caj_codigo',
                'header' => 'Código',
            ],
            [
                'attribute' => 'caj_anaquel_id',
                'header' => 'Anaquel', // 'label' es un alias de 'header'
                'value' => function ($model) {
                    return $model->cajAnaquel ? $model->cajAnaquel->ana_nombre : 'N/A';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Anaquel::find()->all(), 'ana_id', 'ana_nombre'),
            ],
            [
                'attribute' => 'caj_nivel_id',
                'header' => 'Nivel',
                'value' => function ($model) {
                    return $model->cajNivel ? $model->cajNivel->niv_nombre : 'N/A';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\app\models\Nivelalmacenamiento::find()->all(), 'niv_id', 'niv_nombre'),
            ],
            // La columna de acciones y la de ID han sido eliminadas
        ],
    ]); ?>

</div>