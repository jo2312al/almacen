<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Fondo $model */

$this->title = 'Create Fondo';
$this->params['breadcrumbs'][] = ['label' => 'Fondos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fondo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
