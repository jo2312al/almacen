<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SeccionSerie $model */

$this->title = 'Update Seccion Serie: ' . $model->sec_id;
$this->params['breadcrumbs'][] = ['label' => 'Seccion Series', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sec_id, 'url' => ['view', 'sec_id' => $model->sec_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="seccion-serie-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
