<?php

use yii\web\View;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Escáner QR';
// Registramos la librería para el escáner QR en la cabecera.
$this->registerJsFile('https://cdn.jsdelivr.net/npm/html5-qrcode/minified/html5-qrcode.min.js', ['position' => View::POS_HEAD]);
?>

<div class="qr-scanner-container">
    <h2 class="display-5 mb-4 fw-bold"><?= Html::encode($this->title) ?></h2>

    <!-- Marco visual para el escáner QR. La clase 'qr-scanner-frame' define el contenedor exterior. -->
    <div class="qr-scanner-frame">
        <!-- El div #qr-reader ahora tiene un tamaño fijo definido en el CSS para evitar saltos de layout. -->
        <div id="qr-reader"></div>
    </div>

    <p class="scanner-instructions">
        <i class="bi bi-upc-scan me-2"></i>
        Apunta con la cámara al código QR
    </p>
</div>

<!-- Modal de Bootstrap 5 para mostrar el resultado del escaneo -->
<div class="modal fade" id="qrResultModal" tabindex="-1" aria-labelledby="qrResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrResultModalLabel">
                    <i class="bi bi-check-circle-fill me-2 text-success"></i>Código Detectado
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">Se ha escaneado el siguiente enlace:</p>
                <div id="qr-result-link" class="text-start"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Escanear de nuevo</button>
                <a href="#" class="btn btn-primary" id="continue-button" target="_blank" rel="noopener noreferrer">
                    <i class="bi bi-box-arrow-up-right me-2"></i>
                    Continuar
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const html5QrCode = new Html5Qrcode("qr-reader");
        const qrResultModalElement = document.getElementById('qrResultModal');
        const qrModal = new bootstrap.Modal(qrResultModalElement);

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        };

        const startScanner = () => {
            if (html5QrCode.isScanning) return;
            
            const qrReaderElement = document.getElementById('qr-reader');
            qrReaderElement.innerHTML = `
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2">Iniciando cámara...</span>
                </div>`;
            
            html5QrCode.start(
                { facingMode: "environment" },
                config,
                onScanSuccess,
                (error) => {}
            ).catch(err => {
                console.error(`Error al iniciar el escáner: ${err}`);
                qrReaderElement.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Error de cámara. Por favor, verifica los permisos.
                    </div>`;
            });
        };

        const onScanSuccess = (decodedText, decodedResult) => {
            html5QrCode.stop().then(() => {
                document.getElementById('qr-result-link').textContent = decodedText;
                document.getElementById('continue-button').href = decodedText;
                qrModal.show();
            }).catch(err => console.error("Error al detener la cámara:", err));
        };

        qrResultModalElement.addEventListener('hidden.bs.modal', () => {
            startScanner();
        });

        startScanner();
    });
</script>
