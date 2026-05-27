<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\SeccionSerie $model */

$this->title = $model->sec_id;
$this->params['breadcrumbs'][] = ['label' => 'Seccion Series', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="seccion-serie-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'sec_id' => $model->sec_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'sec_id' => $model->sec_id], [
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
            'sec_id',
            'sec_codigo',
            'sec_descripcion',
        ],
    ]) ?>

</div>
