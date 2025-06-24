<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Archivo; // Modelo de Archivo
use app\models\Anaquel; // Modelo de Anaquel
use app\models\Nivelalmacenamiento; // Modelo de Nivelalmacenamiento

/** @var yii\web\View $this */
/** @var app\models\Caja $model */

$this->title = 'Caja: ' . $model->caj_codigo;
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caja-view">

    <h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Update', ['update', 'id' => $model->caj_id], ['class' => 'btn btn-primary']) ?>
    <?= \yii\helpers\Html::a('Generar QR', ['caja/generar-qr', 'caj_id' => $model->caj_id], [
        'class' => 'btn btn-primary',
    ]) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->caj_id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
</p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'caj_id',
            'caj_codigo',
            [
                'attribute' => 'caj_anaquel_id',
                'label' => 'Anaquel',
                'value' => $model->cajAnaquel ? $model->cajAnaquel->ana_nombre : 'No asignado',
            ],
            [
                'attribute' => 'caj_nivel_id',
                'label' => 'Nivel',
                'value' => $model->cajNivel ? $model->cajNivel->niv_nombre : 'No asignado',
            ],
        ],
    ]) ?>

    <h2>Archivos relacionados</h2>

    <?= GridView::widget([
        'dataProvider' => new yii\data\ActiveDataProvider([
            'query' => $model->getArchivos(),
            'pagination' => [
                'pageSize' => 10, // Puedes ajustar la cantidad de archivos por página
            ],
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'arc_codigo',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}', // Solo muestra el botón de "View"
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['archivo/view', 'arc_id' => $model->arc_id], [
                            'class' => 'btn btn-info',
                            'title' => 'View Details',
                            'data-pjax' => '0', // Opcional para evitar el uso de PJAX
                        ]);
                    },
                ],
            ],
        ],
    ]) ?>

</div>
