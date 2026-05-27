<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Alumno $model */

$this->title = 'Create Alumno';
$this->params['breadcrumbs'][] = ['label' => 'Alumnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alumno-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
     <p>
    <!-- Botón para generar el código QR -->
    <?= Html::a('Generar QR', ['alumno/generar-qr'], [
        'class' => 'btn btn-success',
        'target' => '_blank'  // Esto hace que el archivo se abra en una nueva pestaña
    ]) ?>
    </p>

</div>
