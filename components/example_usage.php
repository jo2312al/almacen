<?php
require_once 'QRCodeGenerator.php';

use app\components\QRCodeGenerator;

try {
    // Instanciar la clase
    $qrGenerator = new QRCodeGenerator('./qrcodes/', 'Q', 6);

    // Generar QR para una URL
    $url = 'https://ejemplo.com';
    $filename = 'mi_qr';

    // Generar solo JPG
    $jpgPath = $qrGenerator->generateJPG($url, $filename);
    echo "JPG generado en: $jpgPath\n";

    // Generar solo PDF
    $pdfPath = $qrGenerator->generatePDF($url, $filename, 'Mi Código QR');
    echo "PDF generado en: $pdfPath\n";

    // Generar ambos
    $files = $qrGenerator->generateBoth($url, $filename . '_both', 'Mi Código QR');
    echo "JPG generado en: {$files['jpg']}\n";
    echo "PDF generado en: {$files['pdf']}\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
