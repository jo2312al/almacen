<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\AreaGeneradora $model */

$this->title = 'Update Area Generadora: ' . $model->are_id;
$this->params['breadcrumbs'][] = ['label' => 'Area Generadoras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->are_id, 'url' => ['view', 'are_id' => $model->are_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="area-generadora-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
