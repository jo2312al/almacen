<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Caja;

/** @var yii\web\View $this */
/** @var app\models\Anaquel $model */

$this->title = $model->ana_nombre;
$this->params['breadcrumbs'][] = ['label' => 'Anaqueles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// --- JavaScript para Fila Clickeable en la tabla de Cajas ---
$this->registerJs("
    // Se usa un selector más robusto para asegurar que funcione dentro del nuevo layout
    $('#cajas-grid-view tbody tr').css('cursor', 'pointer').on('click', function() {
        var url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
", \yii\web\View::POS_READY);

?>
<div class="anaquel-view">

    <div class="row g-lg-4">

        <div class="col-lg-4">
            <h1 class="mb-4" style="font-weight: 500;"><?= Html::encode($this->title) ?></h1>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Información del Anaquel</h5>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped detail-view-table mb-0'],
                        'attributes' => [
                            [
                                'attribute' => 'ana_id',
                                'label' => 'ID de Sistema',
                            ],
                            [
                                'attribute' => 'ana_nombre',
                                'label' => 'Nombre / Código',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <h3 class="mb-4">Cajas en este Anaquel</h3>

            <?php
            $dataProvider = new ActiveDataProvider([
                'query' => $model->getCajas(),
                'pagination' => ['pageSize' => 10],
            ]);

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'id' => 'cajas-grid-view',
                'tableOptions' => ['class' => 'table table-hover'],
                'rowOptions' => function ($model, $key, $index, $grid) {
                    return [
                        'data-url' => Url::to(['caja/view', 'caj_id' => $model->caj_id]),
                        'title' => 'Ver detalles de la caja',
                    ];
                },
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn', 'header' => '#'],
                    [
                        'attribute' => 'caj_codigo',
                        'label' => 'Nombre / Código de Caja',
                    ],
                    [
                        'attribute' => 'caj_nivel_id',
                        'label' => 'Nivel',
                    ],
                ],
                'pager' => [
                    'class' => 'yii\bootstrap5\LinkPager',
                    'options' => ['class' => 'pagination justify-content-center mt-4'],
                ],
                'layout' => "{items}\n<div class='d-flex justify-content-center'>{summary}</div>\n{pager}",
            ]);
            ?>
        </div>
    </div>
</div>