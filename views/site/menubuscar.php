<?php
use yii\helpers\Url;
/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="site-index">
<div class="d-flex justify-content-center align-items-center" style="gap: 40px; height: fit-content;">
    <!-- Botón de "Crear" -->
    <div class="d-flex flex-column align-items-center">
        <a href="<?= Url::to(['/alumno/index']) ?>">
            <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <i class="bi bi-person-plus-fill" style="font-size: 2.5rem;"></i>
            </button>
        </a>
        <label class="mt-2 text-center">Alumno</label>
    </div>

    <!-- Botón de "Escanear" -->
    <div class="d-flex flex-column align-items-center">
        <a href="<?= Url::to(['/caja/index']) ?>">
            <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <i class="bi bi-box2-fill" style="font-size: 2.5rem;"></i>
            </button>
        </a>
        <label class="mt-2 text-center">Caja</label>
    </div>

    <!-- Botón de "Buscar" -->
    <div class="d-flex flex-column align-items-center">
        <a href="<?= Url::to(['/anaquel/index']) ?>">
            <button class="btn btn-primary rounded-circle custom-btn" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; padding: 0;">
                <i class="bi bi-hdd-stack-fill" style="font-size: 2.5rem;"></i>
            </button>
        </a>
        <label class="mt-2 text-center">Anaquel</label>
    </div>
</div>
</div>
