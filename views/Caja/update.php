<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Caja $model */

$this->title = 'Update Caja: ' . $model->caj_id;
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->caj_id, 'url' => ['view', 'caj_id' => $model->caj_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="caja-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
