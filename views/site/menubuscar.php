<?php
use app\components\RbacAccess;
use yii\helpers\Url;

/** @var yii\web\View $this */
$this->title = 'Buscar';
$items = [
    ['Alumno', 'bi-person-plus-fill', ['/alumno/index'], RbacAccess::can('alumno.ver')],
    ['Caja', 'bi-box2-fill', ['/caja/index'], RbacAccess::can('caja.ver')],
    ['Anaquel', 'bi-hdd-stack-fill', ['/anaquel/index'], RbacAccess::can('catalogo.ver')],
    ['Documentos', 'bi-file-earmark-text', ['/archivo/index'], RbacAccess::can('archivo.ver')],
    ['Busqueda Global', 'bi-binoculars', ['/busqueda/index'], RbacAccess::can('archivo.ver')],
];
$visibleItems = array_filter($items, static fn($item) => $item[3]);
?>
<div class="site-index">
    <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 40px; height: fit-content;">
        <?php foreach ($visibleItems as [$label, $icon, $url]): ?>
            <div class="d-flex flex-column align-items-center">
                <a href="<?= Url::to($url) ?>">
                    <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                        <i class="bi <?= $icon ?>" style="font-size: 2.5rem;"></i>
                    </button>
                </a>
                <label class="mt-2 text-center"><?= $label ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>