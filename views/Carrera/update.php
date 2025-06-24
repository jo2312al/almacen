<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Carrera $model */

$this->title = 'Update Carrera: ' . $model->car_id;
$this->params['breadcrumbs'][] = ['label' => 'Carreras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->car_id, 'url' => ['view', 'car_id' => $model->car_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="carrera-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
