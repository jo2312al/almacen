<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */
/** @var app\models\Alumno $alumnoModel */

// Título y breadcrumbs traducidos
$this->title = 'Registrar Nuevo Archivo';
$this->params['breadcrumbs'][] = ['label' => 'Archivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archivo-create">

    <h1 style="font-weight: 500;"><?= Html::encode($this->title) ?></h1>
    <p class="lead" style="font-weight: 400;">Siga los pasos para identificar al alumno y generar el código clasificador del archivo.</p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<!-- SPINNER DE CARGA GLOBAL -->
<!-- Este spinner se mostrará mientras se procesa el PDF y se guarda el alumno -->
<div id="loading-spinner" class="loader-wrapper d-none">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Procesando...</span>
    </div>
</div>

<!-- Estilos para el spinner, usando las variables de color del tema -->
<style>
    .loader-wrapper {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        display: flex; justify-content: center; align-items: center;
        z-index: 1060; /* Asegura que esté por encima del modal */
    }
    .spinner-border {
        width: 3.5rem; height: 3.5rem;
        /* El color se tomará de la variable de color primario del tema actual (claro/oscuro) */
        color: var(--primary-color, #2ECC71); 
    }
</style>

<!-- MODAL PARA REVISIÓN/CREACIÓN DE ALUMNO -->
<!-- El contenido de este modal se cargará dinámicamente con AJAX -->
<div class="modal fade" id="alumno-modal" tabindex="-1" aria-labelledby="alumnoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alumnoModalLabel" style="font-weight: 500;">Revisar Datos del Alumno</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-center">Cargando formulario...</p>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * ===================================================================
 * CORRECCIÓN FINAL
 * Se añade 'true' a cada Url::to() para generar una URL absoluta.
 * Esto soluciona el error "Failed to construct 'URL': Invalid URL".
 * ===================================================================
 */
$processPdfUrl = Url::to(['archivo/process-pdf'], true);
$createAlumnoUrl = Url::to(['alumno/create'], true);
$getAlumnoInfoUrl = Url::to(['alumno/get-alumno-info'], true); 

$this->registerJsVar('processPdfUrl', $processPdfUrl);
$this->registerJsVar('createAlumnoUrl', $createAlumnoUrl);
$this->registerJsVar('getAlumnoInfoUrl', $getAlumnoInfoUrl);
?>