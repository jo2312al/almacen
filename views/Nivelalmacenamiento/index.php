<?php

use app\models\Nivelalmacenamiento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\NivelalmacenamientoSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Nivelalmacenamientos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nivelalmacenamiento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Nivelalmacenamiento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'niv_id',
            'niv_nombre',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Nivelalmacenamiento $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'niv_id' => $model->niv_id]);
                 }
            ],
        ],
    ]); ?>


</div>
