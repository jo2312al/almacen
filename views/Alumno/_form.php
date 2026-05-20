<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Generacion;
use app\models\Servicio;
use app\models\Carrera;

/** @var yii\web\View $this */
/** @var app\models\Alumno $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <div class="col-md-10">
        <?php $form = ActiveForm::begin([
            'id' => 'modal-alumno-form',
            'options' => ['enctype' => 'multipart/form-data'],
            'enableAjaxValidation' => false,
        ]); ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'alu_matricula')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'alu_paterno')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'alu_materno')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'alu_nombre')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row"> <div class="col-md-4">
                <?= $form->field($model, 'alu_ingreso')->input('number', ['min' => 1900, 'max' => date('Y'), 'placeholder' => 'Año de ingreso']) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'alu_servicio_id')->dropDownList(
                    ArrayHelper::map(
                        Servicio::find()->all(),
                        'ser_id',
                        function ($model) {
                            return $model->ser_anio . ' - ' . ($model->ser_periodo_id == 1 ? 'Enero-Julio' : 'Julio-Diciembre');
                        }
                    ),
                    ['prompt' => 'Seleccione un servicio']
                ) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'alu_carrera_id')->dropDownList(
                    ArrayHelper::map(Carrera::find()->all(), 'car_id', 'car_nombre'),
                    ['prompt' => 'Seleccione una carrera']
                ) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'id' => 'btn-guardar-alumno']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-2">
    </div>
</div>

<?php
// --- SCRIPT PARA FORZAR ENVÍO CON ENTER ---
// Detecta si se presiona una tecla dentro del formulario
$script = <<< JS
$('#modal-alumno-form input, #modal-alumno-form select').on('keypress', function(e) {
    // Si la tecla es Enter (código 13)
    if (e.which == 13) {
        e.preventDefault(); // Evita el comportamiento por defecto (saltos de línea o recargas raras)
        $('#modal-alumno-form').trigger('submit'); // Dispara el evento submit que tu AJAX ya está escuchando
    }
});
JS;
$this->registerJs($script);
?>