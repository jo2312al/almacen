<?php

namespace app\controllers;

use app\models\BitacoraAccion;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class BitacoraController extends Controller
{
    public function behaviors()
    {
        return [
            'ghost-access'=> [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BitacoraAccion::find()->orderBy(['bit_id' => SORT_DESC]),
            'pagination' => ['pageSize' => 30],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
