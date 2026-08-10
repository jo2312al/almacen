<?php

namespace app\controllers;

use app\models\Archivo;
use app\models\Caja;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class BusquedaController extends Controller
{
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    public function actionIndex($q = '')
    {
        $query = Archivo::find()
            ->alias('archivo')
            ->joinWith(['arcAlumno alumno', 'arcCaja caja'])
            ->orderBy(['archivo.arc_id' => SORT_DESC]);

        $q = trim($q);
        if ($q !== '') {
            $query->andWhere([
                'or',
                ['like', 'archivo.arc_codigo', $q],
                ['like', 'archivo.arc_nombre_documento', $q],
                ['like', 'alumno.alu_matricula', $q],
                ['like', 'alumno.alu_nombre', $q],
                ['like', 'alumno.alu_paterno', $q],
                ['like', 'alumno.alu_materno', $q],
                ['like', 'caja.caj_codigo', $q],
            ]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'q' => $q,
            'dataProvider' => $dataProvider,
        ]);
    }

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
        $cajasAnaquel = [];
        if ($caja !== null && $caja->caj_anaquel_id !== null) {
            $cajasAnaquel = Caja::find()
                ->with(['cajAnaquel', 'cajNivel', 'archivos'])
                ->where(['caj_anaquel_id' => $caja->caj_anaquel_id])
                ->orderBy(['caj_nivel_id' => SORT_ASC, 'caj_codigo' => SORT_ASC])
                ->all();
        }

        return $this->render('localizar', [
            'archivo' => $archivo,
            'caja' => $caja,
            'cajasAnaquel' => $cajasAnaquel,
        ]);
    }
}