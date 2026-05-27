<?php

namespace app\controllers;

use Yii;
use app\models\Anaquel;
use app\models\AnaquelSearch;
use app\services\AnaquelService; // <--- Importamos el servicio
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AnaquelController implements the CRUD actions for Anaquel model.
 */
class AnaquelController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'ghost-access'=> [
                    'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'create' => ['POST'], // Mantenemos la restricción POST
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Anaquel models.
     */
    public function actionIndex()
    {
        $searchModel = new AnaquelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Anaquel model.
     */
    public function actionView($ana_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($ana_id),
        ]);
    }

    /**
     * Creates a new Anaquel model utilizing the Service logic.
     * @return \yii\web\Response JSON response
     */
    public function actionCreate()
    {
        // 1. Configuramos la respuesta como JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // 2. Instanciamos el servicio
        $service = new AnaquelService();

        // 3. Delegamos la creación al servicio y retornamos su respuesta
        return $service->createNextAnaquel();
    }

    /**
     * Updates an existing Anaquel model.
     */
    public function actionUpdate($ana_id)
    {
        $model = $this->findModel($ana_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ana_id' => $model->ana_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Anaquel model.
     */
    public function actionDelete($ana_id)
    {
        $this->findModel($ana_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Anaquel model based on its primary key value.
     */
    protected function findModel($ana_id)
    {
        if (($model = Anaquel::findOne(['ana_id' => $ana_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}