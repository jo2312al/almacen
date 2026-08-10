<?php

namespace app\controllers;

use app\models\Caja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class ReporteController extends Controller
{
    /**
     * Restringe el modulo de reportes a usuarios autorizados por Webvimark.
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

        $contenido = '';
        foreach ($lineas as $linea) {
            $contenido .= implode(',', array_map([$this, 'formatearCampoCsv'], $linea)) . "\r\n";
        }

        return \Yii::$app->response->sendContentAsFile(
            "\xEF\xBB\xBF" . $contenido,
            'reporte_cajas_' . date('Ymd_His') . '.csv',
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