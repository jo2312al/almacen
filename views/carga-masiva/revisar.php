<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Alumno $model */
/** @var app\models\CargaMasivaDetalle $detail */

$this->title = 'Revisar Alumno Pendiente';
$this->params['breadcrumbs'][] = ['label' => 'Cargas Masivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Carga #' . $detail->det_carga_id, 'url' => ['view', 'id' => $detail->det_carga_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="carga-masiva-revisar">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        Revisa los datos detectados para <strong><?= Html::encode($detail->det_nombre_original) ?></strong>.
        Al guardar, se creará el alumno si no existe y el PDF pendiente quedará asociado a la caja.
    </div>

    <?= $this->render('/alumno/_form', ['model' => $model]) ?>
</div>
