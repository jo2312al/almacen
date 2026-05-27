<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */

$this->title = 'Create Archivo';
$this->params['breadcrumbs'][] = ['label' => 'Archivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archivo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
