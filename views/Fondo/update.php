<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Fondo $model */

$this->title = 'Update Fondo: ' . $model->fon_id;
$this->params['breadcrumbs'][] = ['label' => 'Fondos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fon_id, 'url' => ['view', 'fon_id' => $model->fon_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fondo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
