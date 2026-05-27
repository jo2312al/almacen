<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Generacion $model */

$this->title = 'Update Generacion: ' . $model->gen_id;
$this->params['breadcrumbs'][] = ['label' => 'Generacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->gen_id, 'url' => ['view', 'gen_id' => $model->gen_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="generacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
