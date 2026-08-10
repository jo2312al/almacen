<?php

namespace app\commands;

use app\models\Alumno;
use app\models\Anaquel;
use app\models\Archivo;
use app\models\AreaGeneradora;
use app\models\Caja;
use app\models\CargaMasiva;
use app\models\CargaMasivaDetalle;
use app\models\Carrera;
use app\models\ClaveProgramatica;
use app\models\Fondo;
use app\models\Generacion;
use app\models\Nivelalmacenamiento;
use app\models\Periodo;
use app\models\SeccionSerie;
use app\models\Servicio;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\FileHelper;

class DemoController extends Controller
{
    /**
     * Carga datos ficticios para demostrar dashboard, búsqueda, localizador, QR y reportes.
     */
    public function actionSeed()
    {
        $fechaDemo = '2026-08-10 09:00:00';

        $periodo = $this->buscarOCrear(Periodo::class, ['per_nombre' => 'Enero-Julio']);
        $servicio = $this->buscarOCrear(Servicio::class, ['ser_anio' => '2026', 'ser_periodo_id' => $periodo->per_id]);
        $generacion = $this->buscarOCrear(Generacion::class, ['gen_nombre' => '2021-2026']);
        $carrera = $this->buscarOCrear(Carrera::class, ['car_nombre' => 'Ingeniería en Sistemas Computacionales']);

        $fondo = $this->buscarOCrear(Fondo::class, ['fon_codigo' => '03', 'fon_descripcion' => 'Servicios Escolares'], ['fon_codigo' => '03']);
        $clave = $this->buscarOCrear(ClaveProgramatica::class, ['cla_codigo' => '511', 'cla_descripcion' => 'Control escolar de alumnos'], ['cla_codigo' => '511']);
        $area = $this->buscarOCrear(AreaGeneradora::class, ['are_codigo' => 'SE', 'are_descripcion' => 'Servicios Escolares'], ['are_codigo' => 'SE']);
        $seccion = $this->buscarOCrear(SeccionSerie::class, ['sec_codigo' => 'ALU', 'sec_descripcion' => 'Expedientes de alumnos'], ['sec_codigo' => 'ALU']);

        $anaquel = $this->buscarOCrear(Anaquel::class, ['ana_nombre' => 'Anaquel Demo A']);
        $nivelSuperior = $this->buscarOCrear(Nivelalmacenamiento::class, ['niv_nombre' => 'Nivel Superior']);
        $nivelMedio = $this->buscarOCrear(Nivelalmacenamiento::class, ['niv_nombre' => 'Nivel Medio']);

        $cajaPrincipal = $this->buscarOCrear(Caja::class, [
            'caj_codigo' => 'AC01D0001',
            'caj_anaquel_id' => $anaquel->ana_id,
            'caj_nivel_id' => $nivelSuperior->niv_id,
        ], ['caj_codigo' => 'AC01D0001']);
        $this->buscarOCrear(Caja::class, [
            'caj_codigo' => 'AC01D0002',
            'caj_anaquel_id' => $anaquel->ana_id,
            'caj_nivel_id' => $nivelMedio->niv_id,
        ], ['caj_codigo' => 'AC01D0002']);

        $alumnosGuardados = [];
        foreach ($this->alumnosDemo() as $datosAlumno) {
            $alumnosGuardados[] = $this->buscarOCrear(Alumno::class, array_merge($datosAlumno, [
                'alu_generacion_id' => $generacion->gen_id,
                'alu_ingreso' => '2021-08-16',
                'alu_servicio_id' => $servicio->ser_id,
                'alu_carrera_id' => $carrera->car_id,
            ]), ['alu_matricula' => $datosAlumno['alu_matricula']]);
        }

        $archivosGuardados = [];
        foreach ($this->archivosDemo($alumnosGuardados) as [$alumno, $nombreDocumento, $codigo, $nombrePdf]) {
            $ruta = $this->crearPdfDemo($nombrePdf, $nombreDocumento, $alumno->getNombreCompleto(), $alumno->alu_matricula);
            $archivosGuardados[] = $this->buscarOCrear(Archivo::class, [
                'arc_codigo' => $codigo,
                'arc_nombre_documento' => $nombreDocumento,
                'arc_caja_id' => $cajaPrincipal->caj_id,
                'arc_alumno_id' => $alumno->alu_id,
                'arc_ruta' => $ruta,
                'arc_fondo_id' => $fondo->fon_id,
                'arc_clave_programatica_id' => $clave->cla_id,
                'arc_area_generadora_id' => $area->are_id,
                'arc_seccion_serie_id' => $seccion->sec_id,
            ], ['arc_codigo' => $codigo]);
        }

        $lote = $this->buscarOCrear(CargaMasiva::class, [
            'car_caja_id' => $cajaPrincipal->caj_id,
            'car_estado' => CargaMasiva::ESTADO_FINALIZADA,
            'car_total' => 3,
            'car_exitosos' => 1,
            'car_pendientes' => 1,
            'car_errores' => 1,
            'car_creado_en' => $fechaDemo,
            'car_finalizado_en' => $fechaDemo,
            'car_fondo_id' => $fondo->fon_id,
            'car_clave_programatica_id' => $clave->cla_id,
            'car_area_generadora_id' => $area->are_id,
            'car_seccion_serie_id' => $seccion->sec_id,
        ], ['car_caja_id' => $cajaPrincipal->caj_id, 'car_creado_en' => $fechaDemo]);

        $this->crearDetallesDemo($lote, $archivosGuardados, $alumnosGuardados, $fechaDemo);

        $this->stdout("Datos demo cargados correctamente.\n");
        $this->stdout("Caja: {$cajaPrincipal->caj_codigo}\n");
        $this->stdout("Matrículas: 23990001, 23990002 y pendiente 23990099\n");

        return ExitCode::OK;
    }

    /**
     * Define alumnos ficticios para la demostración.
     */
    private function alumnosDemo()
    {
        return [
            ['alu_matricula' => '23990001', 'alu_nombre' => 'María Fernanda', 'alu_paterno' => 'López', 'alu_materno' => 'García'],
            ['alu_matricula' => '23990002', 'alu_nombre' => 'Carlos Eduardo', 'alu_paterno' => 'Ramírez', 'alu_materno' => 'Torres'],
        ];
    }

    /**
     * Define expedientes ficticios asociados a los alumnos demo.
     */
    private function archivosDemo(array $alumnosGuardados)
    {
        return [
            [$alumnosGuardados[0], 'Constancia de Estudios', '03/511/SE/ALU/23990001/2026', 'constancia_23990001.pdf'],
            [$alumnosGuardados[0], 'Liberación de Servicio Social', '03/511/SE/ALU/23990001-SS/2026', 'servicio_social_23990001.pdf'],
            [$alumnosGuardados[1], 'Kárdex Académico', '03/511/SE/ALU/23990002/2026', 'kardex_23990002.pdf'],
        ];
    }

    /**
     * Crea detalles de carga masiva para mostrar guardado, pendiente y error.
     */
    private function crearDetallesDemo(CargaMasiva $lote, array $archivosGuardados, array $alumnosGuardados, $fechaDemo)
    {
        $this->buscarOCrear(CargaMasivaDetalle::class, [
            'det_carga_id' => $lote->car_id,
            'det_archivo_id' => $archivosGuardados[0]->arc_id,
            'det_alumno_id' => $alumnosGuardados[0]->alu_id,
            'det_nombre_original' => 'demo_constancia_guardada.pdf',
            'det_matricula_detectada' => $alumnosGuardados[0]->alu_matricula,
            'det_estado' => CargaMasivaDetalle::ESTADO_GUARDADO,
            'det_mensaje' => 'Archivo demo guardado correctamente.',
            'det_creado_en' => $fechaDemo,
        ], ['det_carga_id' => $lote->car_id, 'det_nombre_original' => 'demo_constancia_guardada.pdf']);

        $this->buscarOCrear(CargaMasivaDetalle::class, [
            'det_carga_id' => $lote->car_id,
            'det_nombre_original' => 'demo_alumno_pendiente.pdf',
            'det_matricula_detectada' => '23990099',
            'det_estado' => CargaMasivaDetalle::ESTADO_PENDIENTE,
            'det_mensaje' => 'Alumno demo no registrado. Revisar antes de guardar.',
            'det_creado_en' => $fechaDemo,
            'det_ruta_temporal' => $this->crearPdfDemo('pendiente_23990099.pdf', 'Documento pendiente', 'Alumno Demo Pendiente', '23990099'),
            'det_datos_extraidos' => json_encode([
                'alu_matricula' => '23990099',
                'alu_nombre' => 'Ana Paola',
                'alu_paterno' => 'Martínez',
                'alu_materno' => 'Santos',
            ], JSON_UNESCAPED_UNICODE),
        ], ['det_carga_id' => $lote->car_id, 'det_nombre_original' => 'demo_alumno_pendiente.pdf']);

        $this->buscarOCrear(CargaMasivaDetalle::class, [
            'det_carga_id' => $lote->car_id,
            'det_nombre_original' => 'demo_pdf_error.pdf',
            'det_estado' => CargaMasivaDetalle::ESTADO_ERROR,
            'det_mensaje' => 'PDF demo ilegible para mostrar control de errores.',
            'det_creado_en' => $fechaDemo,
        ], ['det_carga_id' => $lote->car_id, 'det_nombre_original' => 'demo_pdf_error.pdf']);
    }

    /**
     * Busca un registro por una llave natural; si no existe, lo crea o actualiza con datos demo.
     */
    private function buscarOCrear($claseModelo, array $atributos, array $llave = null)
    {
        $llave = $llave ?: $atributos;
        $modelo = $claseModelo::findOne($llave) ?: new $claseModelo();
        $modelo->setAttributes($atributos, false);
        $modelo->save(false);

        return $modelo;
    }

    /**
     * Genera un PDF mínimo de demostración y regresa la ruta relativa almacenada.
     */
    private function crearPdfDemo($nombreArchivo, $titulo, $alumno, $matricula)
    {
        $directorio = Yii::getAlias('@app/web/archivos/demo');
        FileHelper::createDirectory($directorio);

        $rutaAbsoluta = $directorio . DIRECTORY_SEPARATOR . $nombreArchivo;
        if (!is_file($rutaAbsoluta)) {
            $lineas = ['ASSRP - Documento demo', $titulo, 'Alumno: ' . $alumno, 'Matricula: ' . $matricula];
            $comandosTexto = 'BT /F1 18 Tf 72 720 Td ';
            foreach ($lineas as $indice => $linea) {
                if ($indice > 0) {
                    $comandosTexto .= '0 -28 Td ';
                }
                $comandosTexto .= '(' . $this->limpiarTextoPdf($linea) . ') Tj ';
            }
            $comandosTexto .= 'ET';

            $objetos = [
                '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
                '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
                '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj',
                '4 0 obj << /Length ' . strlen($comandosTexto) . " >> stream\n" . $comandosTexto . "\nendstream endobj",
                '5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj',
            ];

            $pdf = "%PDF-1.4\n";
            $offsets = [0];
            foreach ($objetos as $objeto) {
                $offsets[] = strlen($pdf);
                $pdf .= $objeto . "\n";
            }

            $inicioXref = strlen($pdf);
            $pdf .= "xref\n0 " . (count($objetos) + 1) . "\n";
            $pdf .= "0000000000 65535 f \n";
            for ($i = 1; $i <= count($objetos); $i++) {
                $pdf .= sprintf('%010d 00000 n ', $offsets[$i]) . "\n";
            }
            $pdf .= "trailer << /Size " . (count($objetos) + 1) . " /Root 1 0 R >>\n";
            $pdf .= "startxref\n" . $inicioXref . "\n%%EOF\n";

            file_put_contents($rutaAbsoluta, $pdf);
        }

        return 'archivos/demo/' . $nombreArchivo;
    }

    /**
     * Limpia caracteres problemáticos para el PDF mínimo generado sin dependencias externas.
     */
    private function limpiarTextoPdf($texto)
    {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
        return str_replace(['\\', '(', ')'], ['/', '[', ']'], $texto ?: '');
    }
}