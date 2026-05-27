<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Alumno $model */

$this->title = 'Update Alumno: ' . $model->alu_id;
$this->params['breadcrumbs'][] = ['label' => 'Alumnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->alu_id, 'url' => ['view', 'alu_id' => $model->alu_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="alumno-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
