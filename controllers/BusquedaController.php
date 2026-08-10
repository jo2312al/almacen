<?php

namespace app\controllers;

use app\models\Archivo;
use app\models\Caja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BusquedaController extends Controller
{
    /**
     * Aplica control de acceso a las herramientas de busqueda.
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
     * Lista expedientes que coinciden con matricula, alumno, caja, documento o clasificador.
     */
    public function actionIndex($q = '')
    {
        $consultaArchivos = Archivo::find()
            ->alias('archivo')
            ->joinWith(['arcAlumno alumno', 'arcCaja caja'])
            ->orderBy(['archivo.arc_id' => SORT_DESC]);

        $textoBusqueda = trim($q);
        if ($textoBusqueda !== '') {
            $consultaArchivos->andWhere([
                'or',
                ['like', 'archivo.arc_codigo', $textoBusqueda],
                ['like', 'archivo.arc_nombre_documento', $textoBusqueda],
                ['like', 'alumno.alu_matricula', $textoBusqueda],
                ['like', 'alumno.alu_nombre', $textoBusqueda],
                ['like', 'alumno.alu_paterno', $textoBusqueda],
                ['like', 'alumno.alu_materno', $textoBusqueda],
                ['like', 'caja.caj_codigo', $textoBusqueda],
            ]);
        }

        $proveedorDatos = new ActiveDataProvider([
            'query' => $consultaArchivos,
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'q' => $textoBusqueda,
            'dataProvider' => $proveedorDatos,
        ]);
    }

    /**
     * Presenta la ubicacion fisica animada de un expediente dentro del archivo.
     */
    public function actionLocalizar($arc_id)
    {
        $archivo = Archivo::find()
            ->with(['arcAlumno', 'arcCaja.cajAnaquel', 'arcCaja.cajNivel'])
            ->where(['arc_id' => $arc_id])
            ->one();

        if ($archivo === null) {
            throw new NotFoundHttpException('El archivo solicitado no existe.');
        }

        $caja = $archivo->arcCaja;
        $cajasDelAnaquel = [];
        if ($caja !== null && $caja->caj_anaquel_id !== null) {
            $cajasDelAnaquel = Caja::find()
                ->with(['cajAnaquel', 'cajNivel', 'archivos'])
                ->where(['caj_anaquel_id' => $caja->caj_anaquel_id])
                ->orderBy(['caj_nivel_id' => SORT_ASC, 'caj_codigo' => SORT_ASC])
                ->all();
        }

        return $this->render('localizar', [
            'archivo' => $archivo,
            'caja' => $caja,
            'cajasAnaquel' => $cajasDelAnaquel,
        ]);
    }
}