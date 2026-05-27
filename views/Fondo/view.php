<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Fondo $model */

$this->title = $model->fon_id;
$this->params['breadcrumbs'][] = ['label' => 'Fondos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fondo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'fon_id' => $model->fon_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'fon_id' => $model->fon_id], [
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
            'fon_id',
            'fon_codigo',
            'fon_descripcion',
        ],
    ]) ?>

</div>
