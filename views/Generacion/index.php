<?php

use app\models\Generacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\GeneracionSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Generacions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="generacion-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Generacion', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'gen_id',
            'gen_nombre',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Generacion $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'gen_id' => $model->gen_id]);
                 }
            ],
        ],
    ]); ?>


</div>
