<?php
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'ASSRP - Inicio';
?>
<div class="site-index text-center">

    <div class="body-content">
        
        <h1 class="display-4 fw-bold">Bienvenido</h1>
        <p class="lead">Selecciona una opción para comenzar a gestionar los archivos.</p>

        <div class="d-flex justify-content-center align-items-center flex-wrap" style="gap: 40px; margin-top: 50px;">
        
            <!-- Botón de "Crear" -->
            <div class="d-flex flex-column align-items-center">
                <a href="<?= Url::to(['/site/menucrear']) ?>" class="btn btn-primary floating-btn custom-btn">
                    <i class="bi bi-plus-circle"></i>
                </a>
                <h5 class="mt-3 btn-label">Crear</h5>
            </div>

            <!-- Botón de "Escanear" -->
            <div class="d-flex flex-column align-items-center">
                <a href="<?= Url::to(['/site/scan']) ?>" class="btn btn-primary floating-btn custom-btn">
                    <i class="bi bi-qr-code-scan"></i>
                </a>
                <h5 class="mt-3 btn-label">Escanear</h5>
            </div>

            <!-- Botón de "Buscar" -->
            <div class="d-flex flex-column align-items-center">
                <a href="<?= Url::to(['/site/menubuscar']) ?>" class="btn btn-primary floating-btn custom-btn">
                    <i class="bi bi-search"></i>
                </a>
                <h5 class="mt-3 btn-label">Buscar</h5>
            </div>
            
        </div>
    </div>
</div>

