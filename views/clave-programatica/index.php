<?php

use app\models\ClaveProgramatica;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ClaveProgramaticaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Clave Programaticas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clave-programatica-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Clave Programatica', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cla_id',
            'cla_codigo',
            'cla_descripcion',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, ClaveProgramatica $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'cla_id' => $model->cla_id]);
                 }
            ],
        ],
    ]); ?>


</div>
