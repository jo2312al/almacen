<?php

use app\models\Fondo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\FondoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Fondos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fondo-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Fondo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fon_id',
            'fon_codigo',
            'fon_descripcion',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Fondo $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'fon_id' => $model->fon_id]);
                 }
            ],
        ],
    ]); ?>


</div>
