<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\AnaquelSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestión de Anaqueles';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="anaquel-index">

    <h1 class="mb-4" style="font-weight: 500;"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(
            '<i class="bi bi-plus-lg me-2"></i>Crear Anaquel',
            ['create'],
            [
                'class' => 'btn btn-primary',
                'id' => 'auto-create-button'
            ]
        ) ?>
    </p>

    <?php
        Modal::begin([
            'title' => '<h5 class="modal-title" id="result-modal-title">Resultado de la Operación</h5>',
            'id' => 'result-modal',
            'size' => 'modal-md',
            'footer' => '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>'
        ]);
        echo "<div id='result-modal-content'></div>";
        Modal::end();
    ?>

    <?php Pjax::begin(['id' => 'anaquel-grid-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover'],
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'data-url' => Url::to(['view', 'ana_id' => $model->ana_id]),
                'style' => 'cursor: pointer;',
                'title' => 'Ver detalles del anaquel',
            ];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'header' => '#'],
           [
                'attribute' => 'ana_nombre',
                'header' => 'Código',
            ],
        ],
        'pager' => [
            'class' => 'yii\bootstrap5\LinkPager',
            'options' => ['class' => 'pagination justify-content-center mt-4'],
        ],
        'layout' => "<div class='table-responsive'>{items}</div>\n<div class='d-flex justify-content-between align-items-center mt-3'><div class='text-muted'>{summary}</div><div>{pager}</div></div>",
    ]); ?>

    <?php Pjax::end(); ?>
</div>

<?php
$this->registerJs("
    $(function() {
        $('#auto-create-button').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var url = button.attr('href');
            
            button.prop('disabled', true).html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Creando...');

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
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
                        
                        $.pjax.reload({container: '#anaquel-grid-pjax'});
                    } else {
                        $('#result-modal-title').text('Error');
                        $('#result-modal-content').html('<div class=\"alert alert-danger\">' + response.message + '</div>');
                        $('#result-modal').modal('show');
                    }
                },
                error: function() {
                    $('#result-modal-title').text('Error de Comunicación');
                    $('#result-modal-content').html('<div class=\"alert alert-danger\">No se pudo completar la operación.</div>');
                    $('#result-modal').modal('show');
                },
                complete: function() {
                    button.prop('disabled', false).html('<i class=\"bi bi-plus-lg me-2\"></i>Crear Anaquel');
                }
            });
        });

        $('#anaquel-grid-pjax').on('click', 'tbody tr', function(e) {
            if ($(e.target).is('a, button, input, .action-column *')) return;
            var url = $(this).data('url');
            if (url) window.location.href = url;
        });
    });
");
?>