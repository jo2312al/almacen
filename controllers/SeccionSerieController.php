<?php

namespace app\controllers;

use app\models\SeccionSerie;
use app\models\SeccionSerieSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SeccionSerieController implements the CRUD actions for SeccionSerie model.
 */
class SeccionSerieController extends Controller
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
     * Lists all SeccionSerie models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SeccionSerieSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SeccionSerie model.
     * @param int $sec_id Sec ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($sec_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($sec_id),
        ]);
    }

    /**
     * Creates a new SeccionSerie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SeccionSerie();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'sec_id' => $model->sec_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SeccionSerie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $sec_id Sec ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($sec_id)
    {
        $model = $this->findModel($sec_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'sec_id' => $model->sec_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SeccionSerie model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $sec_id Sec ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($sec_id)
    {
        $this->findModel($sec_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SeccionSerie model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $sec_id Sec ID
     * @return SeccionSerie the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($sec_id)
    {
        if (($model = SeccionSerie::findOne(['sec_id' => $sec_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
