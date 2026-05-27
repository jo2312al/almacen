<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\SeccionSerie $model */

$this->title = 'Create Seccion Serie';
$this->params['breadcrumbs'][] = ['label' => 'Seccion Series', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seccion-serie-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
