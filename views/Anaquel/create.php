<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Anaquel $model */

$this->title = 'Create Anaquel';
$this->params['breadcrumbs'][] = ['label' => 'Anaquels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="anaquel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
