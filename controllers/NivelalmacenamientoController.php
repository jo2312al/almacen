<?php

namespace app\controllers;

use app\models\Nivelalmacenamiento;
use app\models\NivelalmacenamientoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NivelalmacenamientoController implements the CRUD actions for Nivelalmacenamiento model.
 */
class NivelalmacenamientoController extends Controller
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
     * Lists all Nivelalmacenamiento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new NivelalmacenamientoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Nivelalmacenamiento model.
     * @param int $niv_id Niv ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($niv_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($niv_id),
        ]);
    }

    /**
     * Creates a new Nivelalmacenamiento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Nivelalmacenamiento();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'niv_id' => $model->niv_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Nivelalmacenamiento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $niv_id Niv ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($niv_id)
    {
        $model = $this->findModel($niv_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'niv_id' => $model->niv_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Nivelalmacenamiento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $niv_id Niv ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($niv_id)
    {
        $this->findModel($niv_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Nivelalmacenamiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $niv_id Niv ID
     * @return Nivelalmacenamiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($niv_id)
    {
        if (($model = Nivelalmacenamiento::findOne(['niv_id' => $niv_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
