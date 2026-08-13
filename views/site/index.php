<?php
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'ASSRP';
?>
<div class="site-index">
    <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 40px; height: fit-content;">
        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/site/menucrear']) ?>">
                <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <i class="bi bi-plus-circle" style="font-size: 2.5rem;"></i>
                </button>
            </a>
            <label class="mt-2 text-center">Crear</label>
        </div>

        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/site/scan']) ?>">
                <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <i class="bi bi-qr-code-scan" style="font-size: 2.5rem;"></i>
                </button>
            </a>
            <label class="mt-2 text-center">Escanear</label>
        </div>

        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/site/menubuscar']) ?>">
                <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <i class="bi bi-search" style="font-size: 2.5rem;"></i>
                </button>
            </a>
            <label class="mt-2 text-center">Buscar</label>
        </div>

        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/carga-masiva/create']) ?>">
                <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem;"></i>
                </button>
            </a>
            <label class="mt-2 text-center">Carga Masiva</label>
        </div>

        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/busqueda/index']) ?>">
                <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <i class="bi bi-binoculars" style="font-size: 2.5rem;"></i>
                </button>
            </a>
            <label class="mt-2 text-center">Búsqueda Global</label>
        </div>

        <div class="d-flex flex-column align-items-center">
            <a href="<?= Url::to(['/reporte/cajas']) ?>">
                <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                    <i class="bi bi-journal-text" style="font-size: 2.5rem;"></i>
                </button>
            </a>
            <label class="mt-2 text-center">Reportes</label>
        </div>
    </div>
</div>
