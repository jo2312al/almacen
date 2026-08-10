<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $metrics */
/** @var app\models\CargaMasiva[] $recentLoads */
/** @var app\models\BitacoraAccion[] $recentActions */

$this->title = 'ASSRP - Dashboard';

$cards = [
    ['label' => 'Alumnos', 'value' => $metrics['alumnos'], 'icon' => 'bi-people-fill', 'class' => 'primary'],
    ['label' => 'Cajas', 'value' => $metrics['cajas'], 'icon' => 'bi-archive-fill', 'class' => 'success'],
    ['label' => 'Archivos', 'value' => $metrics['archivos'], 'icon' => 'bi-file-earmark-pdf-fill', 'class' => 'danger'],
    ['label' => 'Cargas masivas', 'value' => $metrics['cargas'], 'icon' => 'bi-cloud-arrow-up-fill', 'class' => 'info'],
    ['label' => 'Pendientes', 'value' => $metrics['pendientes'], 'icon' => 'bi-person-exclamation', 'class' => 'warning'],
    ['label' => 'Errores', 'value' => $metrics['errores'], 'icon' => 'bi-exclamation-triangle-fill', 'class' => 'secondary'],
];
?>

<div class="dashboard-index">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h1 class="mb-1">Panel de control ASSRP</h1>
            <p class="text-muted mb-0">Seguimiento operativo de expedientes, cajas, archivos y cargas masivas.</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-cloud-arrow-up-fill me-1"></i>Carga Masiva', ['/carga-masiva/create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('<i class="bi bi-file-earmark-plus me-1"></i>Nuevo Archivo', ['/archivo/create'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach ($cards as $card): ?>
            <div class="col-6 col-lg-2">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small"><?= Html::encode($card['label']) ?></div>
                                <div class="display-6 fw-bold"><?= Html::encode($card['value']) ?></div>
                            </div>
                            <span class="badge bg-<?= Html::encode($card['class']) ?> fs-6"><i class="bi <?= Html::encode($card['icon']) ?>"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Cargas masivas recientes</strong>
                    <?= Html::a('Ver historial', ['/carga-masiva/index'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead><tr><th>Lote</th><th>Caja</th><th>Estado</th><th>Total</th><th>Guardados</th><th>Pendientes</th><th>Errores</th><th></th></tr></thead>
                        <tbody>
                            <?php if (empty($recentLoads)): ?>
                                <tr><td colspan="8" class="text-muted text-center py-4">Aún no hay cargas masivas registradas.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($recentLoads as $load): ?>
                                <tr>
                                    <td>#<?= Html::encode($load->car_id) ?></td>
                                    <td><?= Html::encode($load->caja ? $load->caja->caj_codigo : 'Sin caja') ?></td>
                                    <td><span class="badge bg-success"><?= Html::encode($load->car_estado) ?></span></td>
                                    <td><?= Html::encode($load->car_total) ?></td>
                                    <td><?= Html::encode($load->car_exitosos) ?></td>
                                    <td><?= Html::encode($load->car_pendientes) ?></td>
                                    <td><?= Html::encode($load->car_errores) ?></td>
                                    <td><?= Html::a('Abrir', ['/carga-masiva/view', 'id' => $load->car_id], ['class' => 'btn btn-sm btn-outline-secondary']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Bitácora reciente</strong>
                    <?= Html::a('Ver todo', ['/bitacora/index'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                </div>
                <div class="list-group list-group-flush">
                    <?php if (empty($recentActions)): ?>
                        <div class="list-group-item text-muted">Aún no hay acciones registradas.</div>
                    <?php endif; ?>
                    <?php foreach ($recentActions as $action): ?>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between gap-2">
                                <strong><?= Html::encode($action->bit_accion) ?></strong>
                                <span class="text-muted small"><?= Html::encode($action->bit_creado_en) ?></span>
                            </div>
                            <div class="small text-muted"><?= Html::encode($action->bit_usuario) ?> · <?= Html::encode($action->bit_entidad) ?> #<?= Html::encode($action->bit_entidad_id) ?></div>
                            <div class="small"><?= Html::encode($action->bit_descripcion) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-3"><?= Html::a('<i class="bi bi-plus-circle me-2"></i>Crear', ['/site/menucrear'], ['class' => 'btn btn-outline-primary w-100 py-3']) ?></div>
        <div class="col-md-3"><?= Html::a('<i class="bi bi-search me-2"></i>Búsqueda Global', ['/busqueda/index'], ['class' => 'btn btn-outline-primary w-100 py-3']) ?></div>
        <div class="col-md-3"><?= Html::a('<i class="bi bi-qr-code-scan me-2"></i>Escanear', ['/site/scan'], ['class' => 'btn btn-outline-primary w-100 py-3']) ?></div>
        <div class="col-md-3"><?= Html::a('<i class="bi bi-journal-text me-2"></i>Bitácora', ['/bitacora/index'], ['class' => 'btn btn-outline-primary w-100 py-3']) ?></div>
        <div class="col-md-3"><?= Html::a('<i class="bi bi-table me-2"></i>Reporte de Cajas', ['/reporte/cajas'], ['class' => 'btn btn-outline-primary w-100 py-3']) ?></div>
    </div>
</div>
