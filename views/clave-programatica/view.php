<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ClaveProgramatica $model */

$this->title = $model->cla_id;
$this->params['breadcrumbs'][] = ['label' => 'Clave Programaticas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="clave-programatica-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'cla_id' => $model->cla_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'cla_id' => $model->cla_id], [
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
            'cla_id',
            'cla_codigo',
            'cla_descripcion',
        ],
    ]) ?>

</div>
