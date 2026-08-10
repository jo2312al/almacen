<?php

namespace app\controllers;

use app\models\Alumno;
use app\models\Caja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ReporteController extends Controller
{
    /**
     * Restringe el módulo de reportes a usuarios autorizados por Webvimark.
     */
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    /**
     * Muestra el reporte operativo de cajas y documentos registrados.
     */
    public function actionCajas()
    {
        $consultaCajas = Caja::find()
            ->with(['cajAnaquel', 'cajNivel', 'archivos'])
            ->orderBy(['caj_codigo' => SORT_ASC]);

        $proveedorDatos = new ActiveDataProvider([
            'query' => $consultaCajas,
            'pagination' => ['pageSize' => 25],
        ]);

        return $this->render('cajas', [
            'proveedorDatos' => $proveedorDatos,
        ]);
    }

    /**
     * Lista alumnos con conteo de expedientes para abrir su reporte individual.
     */
    public function actionAlumnos()
    {
        $consultaAlumnos = Alumno::find()
            ->with(['aluCarrera', 'aluGeneracion', 'aluServicio', 'archivos.arcCaja.cajAnaquel', 'archivos.arcCaja.cajNivel'])
            ->orderBy(['alu_paterno' => SORT_ASC, 'alu_materno' => SORT_ASC, 'alu_nombre' => SORT_ASC]);

        $proveedorDatos = new ActiveDataProvider([
            'query' => $consultaAlumnos,
            'pagination' => ['pageSize' => 25],
        ]);

        return $this->render('alumnos', [
            'proveedorDatos' => $proveedorDatos,
        ]);
    }

    /**
     * Presenta la ficha documental completa de un alumno.
     */
    public function actionAlumno($id)
    {
        $alumno = $this->buscarAlumno($id);
        $proveedorArchivos = new ActiveDataProvider([
            'query' => $alumno->getArchivos()
                ->with(['arcCaja.cajAnaquel', 'arcCaja.cajNivel', 'arcFondo', 'arcClaveProgramatica', 'arcAreaGeneradora', 'arcSeccionSerie'])
                ->orderBy(['arc_id' => SORT_DESC]),
            'pagination' => false,
        ]);

        return $this->render('alumno', [
            'alumno' => $alumno,
            'proveedorArchivos' => $proveedorArchivos,
        ]);
    }

    /**
     * Exporta el reporte de cajas en CSV para abrirlo en Excel o archivarlo.
     */
    public function actionExportarCajas()
    {
        $cajas = Caja::find()
            ->with(['cajAnaquel', 'cajNivel', 'archivos'])
            ->orderBy(['caj_codigo' => SORT_ASC])
            ->all();

        $lineas = [];
        $lineas[] = ['Caja', 'Anaquel', 'Nivel', 'Documentos'];

        foreach ($cajas as $caja) {
            $lineas[] = [
                $caja->caj_codigo,
                $caja->cajAnaquel ? $caja->cajAnaquel->ana_nombre : 'Sin anaquel',
                $caja->cajNivel ? $caja->cajNivel->niv_nombre : 'Sin nivel',
                count($caja->archivos),
            ];
        }

        return $this->descargarCsv($lineas, 'reporte_cajas_' . date('Ymd_His') . '.csv');
    }

    /**
     * Exporta la ficha documental de un alumno en CSV.
     */
    public function actionExportarAlumno($id)
    {
        $alumno = $this->buscarAlumno($id);
        $lineas = [];
        $lineas[] = ['Matrícula', 'Alumno', 'Documento', 'Código clasificador', 'Caja', 'Anaquel', 'Nivel'];

        foreach ($alumno->archivos as $archivo) {
            $caja = $archivo->arcCaja;
            $lineas[] = [
                $alumno->alu_matricula,
                $alumno->getNombreCompleto(),
                $archivo->arc_nombre_documento,
                $archivo->arc_codigo,
                $caja ? $caja->caj_codigo : 'Sin caja',
                $caja && $caja->cajAnaquel ? $caja->cajAnaquel->ana_nombre : 'Sin anaquel',
                $caja && $caja->cajNivel ? $caja->cajNivel->niv_nombre : 'Sin nivel',
            ];
        }

        return $this->descargarCsv($lineas, 'reporte_alumno_' . $alumno->alu_matricula . '_' . date('Ymd_His') . '.csv');
    }

    /**
     * Busca un alumno o lanza error 404 si ya no existe.
     */
    private function buscarAlumno($id)
    {
        $alumno = Alumno::find()
            ->with(['aluCarrera', 'aluGeneracion', 'aluServicio', 'archivos.arcCaja.cajAnaquel', 'archivos.arcCaja.cajNivel'])
            ->where(['alu_id' => $id])
            ->one();

        if ($alumno === null) {
            throw new NotFoundHttpException('El alumno solicitado no existe.');
        }

        return $alumno;
    }

    /**
     * Genera una descarga CSV UTF-8 compatible con Excel.
     */
    private function descargarCsv(array $lineas, $nombreArchivo)
    {
        $contenido = '';
        foreach ($lineas as $linea) {
            $contenido .= implode(',', array_map([$this, 'formatearCampoCsv'], $linea)) . "\r\n";
        }

        return \Yii::$app->response->sendContentAsFile(
            "\xEF\xBB\xBF" . $contenido,
            $nombreArchivo,
            ['mimeType' => 'text/csv; charset=UTF-8']
        );
    }

    /**
     * Escapa un valor para mantener compatible el CSV con Excel.
     */
    private function formatearCampoCsv($valor)
    {
        $valor = (string)$valor;
        return '"' . str_replace('"', '""', $valor) . '"';
    }
}