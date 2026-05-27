<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Nivelalmacenamiento $model */

$this->title = 'Create Nivelalmacenamiento';
$this->params['breadcrumbs'][] = ['label' => 'Nivelalmacenamientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nivelalmacenamiento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
