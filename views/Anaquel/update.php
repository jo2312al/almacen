<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Anaquel $model */

$this->title = 'Update Anaquel: ' . $model->ana_id;
$this->params['breadcrumbs'][] = ['label' => 'Anaquels', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ana_id, 'url' => ['view', 'ana_id' => $model->ana_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="anaquel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
