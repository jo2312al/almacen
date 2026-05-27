<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ClaveProgramatica $model */

$this->title = 'Create Clave Programatica';
$this->params['breadcrumbs'][] = ['label' => 'Clave Programaticas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clave-programatica-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
