<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/** @var yii\web\View $this */
/** @var app\models\Caja $model */

$this->title = 'Detalle de Caja: ' . $model->caj_codigo;
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="caja-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Modificar', ['update', 'caj_id' => $model->caj_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'caj_id' => $model->caj_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de que desea eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="row align-items-center">
        <div class="col-md-7">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    ['label' => 'ID', 'attribute' => 'caj_id'],
                    ['label' => 'Código', 'attribute' => 'caj_codigo'],
                    ['label' => 'Anaquel', 'attribute' => 'caj_anaquel_id', 'value' => $model->cajAnaquel->ana_nombre ?? '(No asignado)'],
                    ['label' => 'Nivel', 'attribute' => 'caj_nivel_id', 'value' => $model->cajNivel->niv_nombre ?? '(No asignado)'],
                ],
            ]) ?>
        </div>
        <div class="col-md-5 text-center">
            <div class="card p-3">
                <h5 class="card-title">Código QR</h5>
                <?php $qrUrl = Url::to(['caja/generar-qr', 'caj_id' => $model->caj_id]); ?>
                <?= Html::img($qrUrl, ['alt' => 'Código QR', 'class' => 'img-fluid mx-auto d-block', 'style' => 'max-width: 250px;']) ?>
                <div class="mt-3">
                    <?= Html::a('<i class="bi bi-download"></i> Descargar QR', $qrUrl, ['class' => 'btn btn-secondary', 'download' => 'qr_caja_' . $model->caj_codigo . '.png']) ?>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h2>Archivos Relacionados</h2>
    <p class="text-muted">Haz clic en cualquier fila para ver los detalles del archivo.</p>

    <?php
        // OPTIMIZACIÓN: Pre-cargamos la relación 'arcAlumno' para evitar consultas N+1.
        $queryArchivos = $model->getArchivos()->with('arcAlumno');
        
        // Creamos el DataProvider con la consulta optimizada.
        $dataProviderArchivos = new ActiveDataProvider(['query' => $queryArchivos]);

        echo GridView::widget([
            'dataProvider' => $dataProviderArchivos, // Usamos el nuevo DataProvider
            'rowOptions' => function ($model, $key, $index, $grid) {
                $url = Url::to(['archivo/view', 'arc_id' => $model->arc_id]);
                return ['onclick' => "window.location.href = '{$url}';", 'style' => 'cursor: pointer;', 'title' => 'Ver detalles'];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Alumno (Matrícula)',
                    'attribute' => 'arc_alumno_id', // El atributo para ordenar sigue siendo la FK
                    'value' => function ($archivoModel) {
                        // Ahora $archivoModel->arcAlumno debería estar cargado y no ser nulo
                        if ($archivoModel->arcAlumno) {
                            // Usamos la función getNombreCompleto() que añadiste al modelo Alumno
                            return Html::encode($archivoModel->arcAlumno->getNombreCompleto() . ' (' . $archivoModel->arcAlumno->alu_matricula . ')');
                        }
                        return '(No asignado)';
                    },
                    'format' => 'raw', // Usamos raw para que Html::encode funcione correctamente
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{download}',
                    'header' => 'Descargar',
                    'buttons' => [
                        'download' => function ($url, $model, $key) {
                            return Html::a('<i class="bi bi-download"></i>',
                                ['archivo/download', 'id' => $model->arc_id],
                                ['class' => 'btn btn-success btn-sm', 'title' => 'Descargar Archivo', 'data-pjax' => '0', 'onclick' => 'event.stopPropagation();']
                            );
                        },
                    ],
                ],
            ],
        ]); 
    ?>
</div>