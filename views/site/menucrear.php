<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;
use yii\helpers\Json;

/** @var yii\web\View $this */
$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="d-flex justify-content-center align-items-center" style="gap: 40px; height: fit-content;">
        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/archivo/create']) ?>"><button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;"><i class="bi bi-person-plus-fill" style="font-size: 2.5rem;"></i></button></a>
            <label class="mt-2 text-center">Archivo</label>
        </div>
        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/caja/create']) ?>"><button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;"><i class="bi bi-box2-fill" style="font-size: 2.5rem;"></i></button></a>
            <label class="mt-2 text-center">Caja</label>
        </div>

        <div class="d-flex flex-column align-items-center">
            <?php
            $form = ActiveForm::begin([
                'action' => ['/anaquel/create'],
                'method' => 'post',
                'id' => 'anaquel-create-form'
            ]);
            ?>
            <button type="submit" id="anaquel-submit-button" class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <i class="bi bi-hdd-stack-fill" style="font-size: 2.5rem;"></i>
            </button>
            <?php ActiveForm::end(); ?>
            <label class="mt-2 text-center">Anaquel</label>
        </div>
    </div>
</div>

<?php
// HTML del Modal (sin cambios)
Modal::begin([
    'title' => '<h5 class="modal-title" id="result-modal-title"></h5>',
    'id' => 'result-modal',
    'size' => 'modal-md',
    'footer' => '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>'
]);
echo "<div id='result-modal-content'></div>";
Modal::end();
?>

<?php
// --- JAVASCRIPT REESCRITO Y A PRUEBA DE FALLOS ---
$this->registerJs("
$(function() {
    // CAMBIO CLAVE: Escuchamos el 'click' en el BOTÓN, no el 'submit' en el FORMULARIO.
    $('#anaquel-submit-button').on('click', function(e) {
        
        // 1. Prevenimos la acción por defecto del botón (que es enviar el formulario)
        e.preventDefault();

        // Obtenemos las referencias al botón y al formulario
        var button = $(this);
        var form = $('#anaquel-create-form'); // Obtenemos el formulario por su ID

        // Deshabilitar botón para evitar múltiples clics
        button.prop('disabled', true).html('<span class=\"spinner-border spinner-border-sm\"></span>');

        // 2. Ejecutamos la llamada AJAX (el resto del código es igual)
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            // No se necesita 'data' porque no estamos enviando campos de formulario
            success: function(response) {
                if (response.success) {
                    var modalContent = '<div class=\"alert alert-success\">' + response.message + '</div>' +
                                       '<h5>Detalles:</h5>' +
                                       '<ul class=\"list-group\">' +
                                       '<li class=\"list-group-item\"><strong>ID:</strong> ' + response.data.ana_id + '</li>' +
                                       '<li class=\"list-group-item\"><strong>Nombre:</strong> ' + response.data.ana_nombre + '</li>' +
                                       '</ul>';
                    
                    $('#result-modal-title').text('¡Éxito!');
                    $('#result-modal-content').html(modalContent);
                    $('#result-modal').modal('show');
                } else {
                    $('#result-modal-title').text('Error');
                    $('#result-modal-content').html('<div class=\"alert alert-danger\">' + (response.message || 'No se pudo crear el anaquel.') + '</div>');
                    $('#result-modal').modal('show');
                }
            },
            error: function() {
                $('#result-modal-title').text('Error de Comunicación');
                $('#result-modal-content').html('<div class=\"alert alert-danger\">No se pudo completar la operación.</div>');
                $('#result-modal').modal('show');
            },
            complete: function() {
                // Reactivar el botón
                button.prop('disabled', false).html('<i class=\"bi bi-hdd-stack-fill\" style=\"font-size: 2.5rem;\"></i>');
            }
        });
    });

    // Evento de cierre de modal (sin cambios)
    $('#result-modal').on('hidden.bs.modal', function () {
        window.location.href = '" . Url::to(['/anaquel/index']) . "';
    });
});
");
?>