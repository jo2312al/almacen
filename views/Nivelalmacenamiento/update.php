<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Nivelalmacenamiento $model */

$this->title = 'Update Nivelalmacenamiento: ' . $model->niv_id;
$this->params['breadcrumbs'][] = ['label' => 'Nivelalmacenamientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->niv_id, 'url' => ['view', 'niv_id' => $model->niv_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nivelalmacenamiento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
