<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\AreaGeneradora $model */

$this->title = $model->are_id;
$this->params['breadcrumbs'][] = ['label' => 'Area Generadoras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="area-generadora-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'are_id' => $model->are_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'are_id' => $model->are_id], [
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
            'are_id',
            'are_codigo',
            'are_descripcion',
        ],
    ]) ?>

</div>
