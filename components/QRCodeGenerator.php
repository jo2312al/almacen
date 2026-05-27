<?php
namespace app\components;

use Exception;
if (!defined('QR_MODE_NUL')) {
    require_once \Yii::getAlias('@vendor/pendalff/phpqrcode/phpqrcode.php');
}

use TCPDF;  // Importar la clase TCPDF

class QRCodeGenerator {
    private $outputDir;
    private $errorCorrectionLevel;
    private $matrixPointSize;

    /**
     * Constructor de la clase
     * @param string $outputDir Directorio donde se guardarán los archivos
     * @param string $errorCorrectionLevel Nivel de corrección de errores (L, M, Q, H)
     * @param int $matrixPointSize Tamaño del punto de la matriz (1-10)
     */
    public function __construct($outputDir = './qrcodes/', $errorCorrectionLevel = 'L', $matrixPointSize = 4) {
        $this->outputDir = rtrim($outputDir, '/') . '/';
        $this->errorCorrectionLevel = $errorCorrectionLevel;
        $this->matrixPointSize = $matrixPointSize;

        // Crear directorio si no existe
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0755, true);
        }
    }

    /**
     * Genera un código QR en formato JPG
     * @param string $data Texto o URL para el QR
     * @param string $filename Nombre del archivo (sin extensión)
     * @return string Ruta del archivo generado
     * @throws Exception Si falla la generación
     */
    public function generateJPG($data, $filename) {
        if (empty($data) || empty($filename)) {
            throw new Exception('Datos y nombre de archivo son requeridos');
        }

        $filePath = $this->outputDir . $filename . '.jpg';
        try {
            QRcode::png($data, $filePath, $this->errorCorrectionLevel, $this->matrixPointSize, 2);
            // Convertir PNG a JPG
            $image = imagecreatefrompng($filePath);
            imagejpeg($image, $filePath, 90);
            imagedestroy($image);
            unlink($filePath . '.png'); // Eliminar PNG temporal
            return $filePath;
        } catch (Exception $e) {
            throw new Exception('Error al generar JPG: ' . $e->getMessage());
        }
    }

    /**
     * Genera un código QR en formato PDF
     * @param string $data Texto o URL para el QR
     * @param string $filename Nombre del archivo (sin extensión)
     * @param string $title Título opcional para el PDF
     * @return string Ruta del archivo generado
     * @throws Exception Si falla la generación
     */
    public function generatePDF($data, $filename, $title = 'Código QR') {
        if (empty($data) || empty($filename)) {
                throw new Exception('Datos y nombre de archivo son requeridos');
        }

        // Generar QR temporal en PNG
        $tempPng = $this->outputDir . 'temp_' . uniqid() . '.png';
        QRcode::png($data, $tempPng, $this->errorCorrectionLevel, $this->matrixPointSize, 2);

        // Crear PDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('QRCodeGenerator');
        $pdf->SetAuthor('Your App');
        $pdf->SetTitle($title);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // Agregar título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, $title, 0, 1, 'C');
        $pdf->Ln(10);

        // Agregar QR
        $pdf->Image($tempPng, 80, 50, 50, 50, 'PNG');

        // Agregar texto del QR
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Ln(60);
        $pdf->Cell(0, 10, 'Contenido: ' . $data, 0, 1, 'C');

        // Guardar PDF
        $filePath = $this->outputDir . $filename . '.pdf';
        $pdf->Output($filePath, 'F');

        // Eliminar PNG temporal
        unlink($tempPng);

        return $filePath;
    }

    /**
     * Genera ambos formatos (JPG y PDF)
     * @param string $data Texto o URL para el QR
     * @param string $filename Nombre del archivo (sin extensión)
     * @param string $title Título opcional para el PDF
     * @return array Rutas de los archivos generados
     * @throws Exception Si falla la generación
     */
    public function generateBoth($data, $filename, $title = 'Código QR') {
        return [
            'jpg' => $this->generateJPG($data, $filename),
            'pdf' => $this->generatePDF($data, $filename, $title)
        ];
    }
}
?>