<?php
use app\components\RbacAccess;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Modal;

/** @var yii\web\View $this */
$this->title = 'Crear';
$items = [
    ['Archivo', 'bi-person-plus-fill', ['/archivo/create'], RbacAccess::can('archivo.crear'), null],
    ['Caja', 'bi-box2-fill', ['/caja/create'], RbacAccess::can('caja.crear'), null],
    ['Anaquel', 'bi-hdd-stack-fill', ['/anaquel/create'], RbacAccess::can('catalogo.administrar'), 'anaquel'],
];
$visibleItems = array_filter($items, static fn($item) => $item[3]);
?>
<div class="site-index">
    <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 40px; height: fit-content;">
        <?php foreach ($visibleItems as [$label, $icon, $url, $visible, $mode]): ?>
            <div class="d-flex flex-column align-items-center">
                <?php if ($mode === 'anaquel'): ?>
                    <?php $form = ActiveForm::begin(['action' => $url, 'method' => 'post', 'id' => 'anaquel-create-form']); ?>
                    <button type="submit" id="anaquel-submit-button" class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="bi <?= $icon ?>" style="font-size: 2.5rem;"></i>
                    </button>
                    <?php ActiveForm::end(); ?>
                <?php else: ?>
                    <a href="<?= Url::to($url) ?>"><button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;"><i class="bi <?= $icon ?>" style="font-size: 2.5rem;"></i></button></a>
                <?php endif; ?>
                <label class="mt-2 text-center"><?= $label ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (RbacAccess::can('catalogo.administrar')): ?>
<?php
Modal::begin([
    'title' => '<h5 class="modal-title" id="result-modal-title"></h5>',
    'id' => 'result-modal',
    'size' => 'modal-md',
    'footer' => '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>'
]);
echo "<div id='result-modal-content'></div>";
Modal::end();
$this->registerJs("
$(function() {
    $('#anaquel-submit-button').on('click', function(e) {
        e.preventDefault();
        var button = $(this);
        var form = $('#anaquel-create-form');
        button.prop('disabled', true).html('<span class=\"spinner-border spinner-border-sm\"></span>');
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#result-modal-title').text('Exito');
                    $('#result-modal-content').html('<div class=\"alert alert-success\">' + response.message + '</div>');
                } else {
                    $('#result-modal-title').text('Error');
                    $('#result-modal-content').html('<div class=\"alert alert-danger\">' + (response.message || 'No se pudo crear el anaquel.') + '</div>');
                }
                $('#result-modal').modal('show');
            },
            error: function() {
                $('#result-modal-title').text('Error de comunicacion');
                $('#result-modal-content').html('<div class=\"alert alert-danger\">No se pudo completar la operacion.</div>');
                $('#result-modal').modal('show');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class=\"bi bi-hdd-stack-fill\" style=\"font-size: 2.5rem;\"></i>');
            }
        });
    });
    $('#result-modal').on('hidden.bs.modal', function () {
        window.location.href = '" . Url::to(['/anaquel/index']) . "';
    });
});
");
?>
<?php endif; ?>