<?php

namespace app\controllers;

use app\models\Fondo;
use app\models\FondoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FondoController implements the CRUD actions for Fondo model.
 */
class FondoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Fondo models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FondoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fondo model.
     * @param int $fon_id Fon ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($fon_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($fon_id),
        ]);
    }

    /**
     * Creates a new Fondo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Fondo();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'fon_id' => $model->fon_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fondo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $fon_id Fon ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($fon_id)
    {
        $model = $this->findModel($fon_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'fon_id' => $model->fon_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Fondo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $fon_id Fon ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($fon_id)
    {
        $this->findModel($fon_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fondo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $fon_id Fon ID
     * @return Fondo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($fon_id)
    {
        if (($model = Fondo::findOne(['fon_id' => $fon_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
