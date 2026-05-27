<?php
use yii\helpers\Html;
?>

<div style="text-align: center; margin-bottom: 20px;">
    <!-- Mostrar el QR de la caja -->
    <h2 style="margin-bottom: 10px;">Código QR de la Caja</h2>
    <img src="<?= $qrCodePath ?>" alt="QR Code" style="width: 15cm; height: 15cm;" />
</div>

<hr style="margin: 20px 0;" />

<div>
    <!-- Encabezado de la lista -->
    <h3 style="text-align: center; margin-bottom: 20px;">Lista de Archivos Contenidos en la Caja</h3>
    <ul style="font-size: 12px; line-height: 1.6; padding-left: 0; list-style-type: none;">
        <?php foreach ($archivos as $archivo): ?>
            <li style="margin-bottom: 5px; border: 1px solid #ddd; padding: 5px; width: 6cm;">
                <?= Html::encode($archivo->codigo) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
