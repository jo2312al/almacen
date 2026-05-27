<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Caja $model */

$this->title = 'Crear Caja';
$this->params['breadcrumbs'][] = ['label' => 'Cajas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="caja-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
