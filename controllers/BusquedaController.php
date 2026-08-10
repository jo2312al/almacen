<?php

namespace app\controllers;

use app\models\Archivo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

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
}
