<?php

use app\models\AreaGeneradora;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AreaGeneradoraSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Area Generadoras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-generadora-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Area Generadora', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'are_id',
            'are_codigo',
            'are_descripcion',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AreaGeneradora $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'are_id' => $model->are_id]);
                 }
            ],
        ],
    ]); ?>


</div>
