<?php

namespace app\controllers;

use app\models\ClaveProgramatica;
use app\models\ClaveProgramaticaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClaveProgramaticaController implements the CRUD actions for ClaveProgramatica model.
 */
class ClaveProgramaticaController extends Controller
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
     * Lists all ClaveProgramatica models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClaveProgramaticaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClaveProgramatica model.
     * @param int $cla_id Cla ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cla_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($cla_id),
        ]);
    }

    /**
     * Creates a new ClaveProgramatica model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ClaveProgramatica();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cla_id' => $model->cla_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ClaveProgramatica model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cla_id Cla ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cla_id)
    {
        $model = $this->findModel($cla_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cla_id' => $model->cla_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ClaveProgramatica model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cla_id Cla ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cla_id)
    {
        $this->findModel($cla_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ClaveProgramatica model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cla_id Cla ID
     * @return ClaveProgramatica the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cla_id)
    {
        if (($model = ClaveProgramatica::findOne(['cla_id' => $cla_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
