<?php

use app\models\SeccionSerie;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SeccionSerieSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Seccion Series';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seccion-serie-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Seccion Serie', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sec_id',
            'sec_codigo',
            'sec_descripcion',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SeccionSerie $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'sec_id' => $model->sec_id]);
                 }
            ],
        ],
    ]); ?>


</div>
