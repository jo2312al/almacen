<?php
use app\components\RbacAccess;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'ASSRP';
$items = [
    ['Crear', 'bi-plus-circle', ['/site/menucrear'], RbacAccess::can('archivo.crear') || RbacAccess::can('caja.crear') || RbacAccess::can('catalogo.administrar')],
    ['Escanear', 'bi-qr-code-scan', ['/site/scan'], RbacAccess::can('caja.ver')],
    ['Buscar', 'bi-search', ['/site/menubuscar'], RbacAccess::can('archivo.ver') || RbacAccess::can('alumno.ver') || RbacAccess::can('caja.ver') || RbacAccess::can('catalogo.ver')],
    ['Carga Masiva', 'bi-cloud-arrow-up', ['/carga-masiva/create'], RbacAccess::can('carga.crear')],
    ['Busqueda Global', 'bi-binoculars', ['/busqueda/index'], RbacAccess::can('archivo.ver')],
    ['Reportes', 'bi-journal-text', ['/site/reportes'], RbacAccess::can('reporte.ver')],
    ['Catalogos', 'bi-collection', ['/site/catalogos'], RbacAccess::can('catalogo.ver')],
    ['Administracion', 'bi-gear', ['/admin'], RbacAccess::can('configuracion.administrar')],
];
$visibleItems = array_filter($items, static fn($item) => $item[3]);
?>
<div class="site-index">
    <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 40px; height: fit-content;">
        <?php foreach ($visibleItems as [$label, $icon, $url]): ?>
            <div class="d-flex flex-column align-items-center">
                <a href="<?= Url::to($url) ?>" aria-label="<?= $label ?>">
                    <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="bi <?= $icon ?>" style="font-size: 2.5rem;"></i>
                    </button>
                </a>
                <label class="mt-2 text-center"><?= $label ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>