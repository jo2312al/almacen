<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Archivo $archivo */
/** @var app\models\Caja|null $caja */
/** @var app\models\Caja[] $cajasAnaquel */

$alumno = $archivo->arcAlumno;
$anaquel = $caja ? $caja->cajAnaquel : null;
$nivel = $caja ? $caja->cajNivel : null;
$nombreAlumno = $alumno ? $alumno->getNombreCompleto() : 'Sin alumno asignado';
$matricula = $alumno ? $alumno->alu_matricula : 'Sin matrícula';
$cajaCodigo = $caja ? $caja->caj_codigo : 'Sin caja';
$anaquelNombre = $anaquel ? $anaquel->ana_nombre : 'Sin anaquel';
$nivelNombre = $nivel ? $nivel->niv_nombre : 'Sin nivel';

$this->title = 'Localizador de Expediente';
$this->params['breadcrumbs'][] = ['label' => 'Búsqueda Global', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.locator-shell {
    display: grid;
    grid-template-columns: minmax(0, 1.5fr) minmax(300px, .8fr);
    gap: 22px;
}
.locator-board,
.locator-side {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 26px rgba(15, 23, 42, .08);
}
.locator-board {
    overflow: hidden;
}
.locator-hero {
    background: linear-gradient(135deg, #0f766e, #1d4ed8);
    color: #fff;
    padding: 28px;
}
.locator-hero h1 {
    font-size: 32px;
    margin: 0 0 8px;
}
.locator-hero p {
    margin: 0;
    opacity: .92;
}
.locator-path {
    display: grid;
    grid-template-columns: repeat(4, minmax(120px, 1fr));
    gap: 12px;
    padding: 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e5e7eb;
}
.path-step {
    position: relative;
    background: #fff;
    border: 1px solid #dbe3ef;
    border-radius: 8px;
    padding: 14px;
    min-height: 86px;
    animation: stepReveal .55s ease both;
}
.path-step:nth-child(2) { animation-delay: .18s; }
.path-step:nth-child(3) { animation-delay: .36s; }
.path-step:nth-child(4) { animation-delay: .54s; }
.path-step span {
    display: block;
    color: #64748b;
    font-size: 12px;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 6px;
}
.path-step strong {
    color: #0f172a;
    font-size: 15px;
}
.locator-map {
    padding: 24px;
}
.shelf-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 18px;
}
.shelf-title h2 {
    font-size: 22px;
    margin: 0;
}
.shelf-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 14px;
}
.box-tile {
    position: relative;
    min-height: 122px;
    border: 1px solid #cbd5e1;
    border-bottom: 9px solid #94a3b8;
    border-radius: 7px;
    background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 14px;
    transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}
.box-tile.target {
    border-color: #22c55e;
    border-bottom-color: #16a34a;
    background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%);
    box-shadow: 0 0 0 5px rgba(34, 197, 94, .18), 0 16px 28px rgba(22, 163, 74, .22);
    transform: translateY(-6px);
    animation: targetPulse 1.35s ease-in-out infinite;
}
.box-code {
    font-weight: 800;
    color: #0f172a;
    font-size: 17px;
}
.box-meta {
    color: #475569;
    font-size: 13px;
    margin-top: 8px;
}
.target-badge {
    position: absolute;
    top: -12px;
    right: 10px;
    color: #fff;
    background: #16a34a;
    border-radius: 999px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 800;
    box-shadow: 0 6px 16px rgba(22, 163, 74, .35);
}
.locator-side {
    padding: 20px;
    align-self: start;
}
.info-list {
    display: grid;
    gap: 12px;
    margin: 16px 0 0;
}
.info-row {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 12px;
}
.info-row span {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
}
.info-row strong {
    display: block;
    color: #111827;
    margin-top: 3px;
    overflow-wrap: anywhere;
}
.empty-map {
    border: 1px dashed #cbd5e1;
    border-radius: 8px;
    padding: 28px;
    color: #64748b;
    background: #f8fafc;
}
@keyframes stepReveal {
    from { opacity: 0; transform: translateY(12px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes targetPulse {
    0%, 100% { box-shadow: 0 0 0 5px rgba(34, 197, 94, .18), 0 16px 28px rgba(22, 163, 74, .22); }
    50% { box-shadow: 0 0 0 10px rgba(34, 197, 94, .10), 0 20px 34px rgba(22, 163, 74, .30); }
}
@media (max-width: 992px) {
    .locator-shell { grid-template-columns: 1fr; }
    .locator-path { grid-template-columns: repeat(2, minmax(120px, 1fr)); }
}
@media (max-width: 576px) {
    .locator-hero h1 { font-size: 25px; }
    .locator-path { grid-template-columns: 1fr; }
}
CSS);
?>

<div class="busqueda-localizar">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="mb-1"><?= Html::encode($this->title) ?></h1>
            <p class="text-muted mb-0"><?= Html::encode($archivo->arc_nombre_documento) ?></p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-search"></i> Busqueda', ['index'], ['class' => 'btn btn-outline-secondary']) ?>
            <?= Html::a('<i class="bi bi-file-earmark-pdf"></i> Descargar', ['/archivo/download', 'id' => $archivo->arc_id], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <div class="locator-shell">
        <section class="locator-board">
            <div class="locator-hero">
                <h1><?= Html::encode($cajaCodigo) ?></h1>
                <p><?= Html::encode($anaquelNombre) ?> / <?= Html::encode($nivelNombre) ?></p>
            </div>

            <div class="locator-path">
                <div class="path-step">
                    <span>Expediente</span>
                    <strong><?= Html::encode($matricula) ?></strong>
                </div>
                <div class="path-step">
                    <span>Anaquel</span>
                    <strong><?= Html::encode($anaquelNombre) ?></strong>
                </div>
                <div class="path-step">
                    <span>Nivel</span>
                    <strong><?= Html::encode($nivelNombre) ?></strong>
                </div>
                <div class="path-step">
                    <span>Caja</span>
                    <strong><?= Html::encode($cajaCodigo) ?></strong>
                </div>
            </div>

            <div class="locator-map">
                <div class="shelf-title">
                    <h2>Mapa del anaquel</h2>
                    <span class="text-muted small"><?= count($cajasAnaquel) ?> caja(s)</span>
                </div>

                <?php if ($caja === null): ?>
                    <div class="empty-map">Este archivo todavía no tiene una caja asignada.</div>
                <?php elseif (empty($cajasAnaquel)): ?>
                    <div class="empty-map">No hay mas cajas registradas en este anaquel.</div>
                <?php else: ?>
                    <div class="shelf-grid">
                        <?php foreach ($cajasAnaquel as $item): ?>
                            <?php $isTarget = (int)$item->caj_id === (int)$caja->caj_id; ?>
                            <div class="box-tile<?= $isTarget ? ' target' : '' ?>">
                                <?php if ($isTarget): ?>
                                    <div class="target-badge">Aquí</div>
                                <?php endif; ?>
                                <div class="box-code"><?= Html::encode($item->caj_codigo) ?></div>
                                <div class="box-meta">
                                    Nivel: <?= Html::encode($item->cajNivel ? $item->cajNivel->niv_nombre : 'Sin nivel') ?><br>
                                    Documentos: <?= Html::encode(count($item->archivos)) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <aside class="locator-side">
            <h2 class="h4 mb-2">Datos del expediente</h2>
            <div class="info-list">
                <div class="info-row">
                    <span>Alumno</span>
                    <strong><?= Html::encode($nombreAlumno) ?></strong>
                </div>
                <div class="info-row">
                    <span>Matrícula</span>
                    <strong><?= Html::encode($matricula) ?></strong>
                </div>
                <div class="info-row">
                    <span>Código clasificador</span>
                    <strong><?= Html::encode($archivo->arc_codigo) ?></strong>
                </div>
                <div class="info-row">
                    <span>Documento</span>
                    <strong><?= Html::encode($archivo->arc_nombre_documento) ?></strong>
                </div>
                <div class="info-row">
                    <span>Ubicación física</span>
                    <strong><?= Html::encode($anaquelNombre) ?> / <?= Html::encode($nivelNombre) ?> / <?= Html::encode($cajaCodigo) ?></strong>
                </div>
            </div>
        </aside>
    </div>
</div>