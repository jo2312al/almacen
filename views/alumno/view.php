<?php 
use yii\helpers\Html;
use yii\widgets\DetailView; // Para renderizar los detalles del modelo
use app\models\Archivo;     // Si necesitas trabajar directamente con el modelo Archivo
use app\models\Generacion;  // Si utilizas el modelo Generacion
use app\models\Servicio;    // Si utilizas el modelo Servicio
use app\models\Periodo;
/** @var yii\web\View $this */
/** @var app\models\Alumno $model */

$this->title = $model->alu_nombre . ' ' . $model->alu_paterno;
$this->params['breadcrumbs'][] = ['label' => 'Alumnos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="alumno-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'alu_id' => $model->alu_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'alu_id' => $model->alu_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '¿Está seguro de que desea eliminar este elemento?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <!-- Información del alumno -->
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'alu_id',
            'alu_matricula',
            'alu_nombre',
            'alu_paterno',
            'alu_materno',
            [
                'label' => 'Generación',
                'value' => $model->aluGeneracion->gen_nombre ?? 'No asignada', // Cambiar a gen_nombre si es el atributo del modelo Generacion
            ],
            [
                'label' => 'Servicio',
'value' => $model->aluServicio ? 
    $model->aluServicio->ser_anio . ' - ' . 
    ($model->aluServicio->ser_periodo_id == 1 ? 'Enero-Julio' : 'Julio-Diciembre') : 
    'No asignado',

            ],
            'alu_ingreso',
        ],
    ]) ?>

    <!-- Archivos asociados -->
    <h3>Archivos Asociados</h3>
<?php if ($model->archivos): ?>
    <ul>
        <?php foreach ($model->archivos as $archivo): ?>
            <li>
                <?= Html::a(
                    $archivo->arc_codigo ?? 'Archivo sin nombre', 
                    ['archivo/view', 'id' => $archivo->arc_id], // Cambia 'arc_id' si el identificador del archivo tiene otro nombre
                    ['target' => '_blank']
                ) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No hay archivos asociados.</p>
<?php endif; ?>


</div>
