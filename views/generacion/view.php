<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Generacion $model */

$this->title = $model->gen_id;
$this->params['breadcrumbs'][] = ['label' => 'Generacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="generacion-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'gen_id' => $model->gen_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'gen_id' => $model->gen_id], [
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
            'gen_id',
            'gen_nombre',
        ],
    ]) ?>

</div>
