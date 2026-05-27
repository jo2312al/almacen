<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClaveProgramatica $model */

$this->title = 'Update Clave Programatica: ' . $model->cla_id;
$this->params['breadcrumbs'][] = ['label' => 'Clave Programaticas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->cla_id, 'url' => ['view', 'cla_id' => $model->cla_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clave-programatica-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
