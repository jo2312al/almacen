<?php

use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
$this->context->layout = 'main-usuario'; // Cambia el layout solo para esta vista.
?>
<div class="site-index">
<div class="d-flex justify-content-center align-items-center" style="gap: 40px; height: fit-content;">
    <!-- Botón de "Crear" -->
    <div class="d-flex flex-column align-items-center">
        <a href="<?= Url::to(['/site/menucrear']) ?>">
            <button class="btn btn-primary rounded-circle" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <img src="/img/crear.png" alt="Crear" style="width: 60px; height: 60px; object-fit: contain;">
            </button>
        </a>
        <label class="mt-2 text-center">Crear</label>
    </div>

    <!-- Botón de "Escanear" -->
    <div class="d-flex flex-column align-items-center">
        <a href="<?= Url::to(['/site/scan']) ?>">
            <button class="btn btn-primary rounded-circle" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <img src="/img/scan.png" alt="Escanear" style="width: 60px; height: 60px; object-fit: contain;">
            </button>
        </a>
        <label class="mt-2 text-center">Escanear</label>
    </div>

    <!-- Botón de "Buscar" -->
    <div class="d-flex flex-column align-items-center">
        <a href="<?= Url::to(['/site/menubuscar']) ?>">
            <button class="btn btn-primary rounded-circle" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <img src="/img/buscar.png" alt="Buscar" style="width: 60px; height: 60px; object-fit: contain;">
            </button>
        </a>
        <label class="mt-2 text-center">Buscar</label>
    </div>
</div>



</div>
