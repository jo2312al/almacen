<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Archivo $model */

$alumno = $model->arcAlumno;
$caja = $model->arcCaja;
$anaquel = $caja ? $caja->cajAnaquel : null;
$nivel = $caja ? $caja->cajNivel : null;

$nombreAlumno = $alumno ? $alumno->getNombreCompleto() : 'Sin alumno asignado';
$matricula = $alumno ? $alumno->alu_matricula : 'Sin matrícula';
$cajaCodigo = $caja ? $caja->caj_codigo : 'Sin caja';
$anaquelNombre = $anaquel ? $anaquel->ana_nombre : 'Sin anaquel';
$nivelNombre = $nivel ? $nivel->niv_nombre : 'Sin nivel';

$this->title = $model->arc_nombre_documento ?: ('Archivo #' . $model->arc_id);
$this->params['breadcrumbs'][] = ['label' => 'Archivos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->registerCss(<<<CSS
.archivo-hero,
.archivo-panel,
.archivo-clasificacion {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 8px 22px rgba(15, 23, 42, .06);
}
.archivo-hero {
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 18px;
    padding: 22px;
    margin-bottom: 18px;
}
.archivo-hero h1 {
    font-size: 28px;
    margin: 0 0 8px;
}
.archivo-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    align-items: flex-start;
    gap: 8px;
}
.archivo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 14px;
    margin-bottom: 18px;
}
.archivo-panel {
    padding: 16px;
}
.archivo-panel span,
.archivo-clasificacion span {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.archivo-panel strong,
.archivo-clasificacion strong {
    display: block;
    color: #0f172a;
    margin-top: 4px;
    overflow-wrap: anywhere;
}
.archivo-clasificacion {
    padding: 18px;
}
.archivo-clasificacion-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
    margin-top: 14px;
}
@media (max-width: 768px) {
    .archivo-hero { grid-template-columns: 1fr; }
    .archivo-actions { justify-content: flex-start; }
}
CSS);
?>

<div class="archivo-view">
    <section class="archivo-hero">
        <div>
            <h1><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0">Código clasificador <?= Html::encode($model->arc_codigo) ?></p>
        </div>
        <div class="archivo-actions">
            <?= Html::a('<i class="bi bi-download"></i> Descargar', ['download', 'id' => $model->arc_id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('<i class="bi bi-geo-alt"></i> Localizar', ['/busqueda/localizar', 'arc_id' => $model->arc_id], ['class' => 'btn btn-info']) ?>
            <?php if ($caja): ?>
                <?= Html::a('<i class="bi bi-qr-code"></i> Vista QR', ['/caja/consulta', 'caj_id' => $caja->caj_id], ['class' => 'btn btn-outline-success']) ?>
            <?php endif; ?>
            <?php if ($alumno): ?>
                <?= Html::a('<i class="bi bi-person-lines-fill"></i> Reporte alumno', ['/reporte/alumno', 'id' => $alumno->alu_id], ['class' => 'btn btn-outline-primary']) ?>
            <?php endif; ?>
        </div>
    </section>

    <div class="archivo-grid">
        <div class="archivo-panel">
            <span>Alumno</span>
            <strong><?= Html::encode($nombreAlumno) ?></strong>
        </div>
        <div class="archivo-panel">
            <span>Matrícula</span>
            <strong><?= Html::encode($matricula) ?></strong>
        </div>
        <div class="archivo-panel">
            <span>Caja</span>
            <strong><?= Html::encode($cajaCodigo) ?></strong>
        </div>
        <div class="archivo-panel">
            <span>Ubicación física</span>
            <strong><?= Html::encode($anaquelNombre) ?> / <?= Html::encode($nivelNombre) ?></strong>
        </div>
    </div>

    <section class="archivo-clasificacion">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <span>Documento</span>
                <strong><?= Html::encode($model->arc_nombre_documento) ?></strong>
            </div>
            <?= Html::a('Editar registro', ['update', 'arc_id' => $model->arc_id], ['class' => 'btn btn-outline-secondary']) ?>
        </div>

        <div class="archivo-clasificacion-grid">
            <div>
                <span>Fondo</span>
                <strong><?= Html::encode($model->arcFondo ? $model->arcFondo->fon_codigo : 'Sin fondo') ?></strong>
            </div>
            <div>
                <span>Clave programática</span>
                <strong><?= Html::encode($model->arcClaveProgramatica ? $model->arcClaveProgramatica->cla_codigo : 'Sin clave') ?></strong>
            </div>
            <div>
                <span>Área generadora</span>
                <strong><?= Html::encode($model->arcAreaGeneradora ? $model->arcAreaGeneradora->are_codigo : 'Sin área') ?></strong>
            </div>
            <div>
                <span>Sección/serie</span>
                <strong><?= Html::encode($model->arcSeccionSerie ? $model->arcSeccionSerie->sec_codigo : 'Sin sección') ?></strong>
            </div>
            <div>
                <span>Ruta almacenada</span>
                <strong><?= Html::encode($model->arc_ruta ?: 'Sin ruta') ?></strong>
            </div>
        </div>
    </section>
</div>