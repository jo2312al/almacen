<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Generacion; // Asegúrate de usar el namespace correcto de tu modelo
use app\models\Servicio;
use app\models\Carrera;

/** @var yii\web\View $this */
/** @var app\models\Alumno $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="row">
    <!-- Columna principal con proporción 10 -->
    <div class="col-md-10">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <!-- Primera línea: Matrícula y Nombre completo -->
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

        <!-- Segunda línea: Generación, Año de ingreso y Servicio -->
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'alu_generacion_id')->dropDownList(
                    ArrayHelper::map(Generacion::find()->all(), 'gen_id', 'gen_nombre'),
                    ['prompt' => 'Seleccione una generación']
                ) ?>
            </div>
            <div class="col-md-4">
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

        <!-- Tercera línea: Carrera -->
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'alu_carrera_id')->dropDownList(
                    ArrayHelper::map(Carrera::find()->all(), 'car_id', 'car_nombre'),
                    ['prompt' => 'Seleccione una carrera']
                ) ?>
            </div>
        </div>

        <!-- Botón Guardar -->
        <div class="form-group">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <!-- Columna secundaria con proporción 2 -->
    <div class="col-md-2">
    </div>
</div>

