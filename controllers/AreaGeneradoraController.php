<?php

namespace app\controllers;

use app\models\AreaGeneradora;
use app\models\AreaGeneradoraSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AreaGeneradoraController implements the CRUD actions for AreaGeneradora model.
 */
class AreaGeneradoraController extends Controller
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
     * Lists all AreaGeneradora models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AreaGeneradoraSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AreaGeneradora model.
     * @param int $are_id Are ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($are_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($are_id),
        ]);
    }

    /**
     * Creates a new AreaGeneradora model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AreaGeneradora();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'are_id' => $model->are_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AreaGeneradora model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $are_id Are ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($are_id)
    {
        $model = $this->findModel($are_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'are_id' => $model->are_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AreaGeneradora model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $are_id Are ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($are_id)
    {
        $this->findModel($are_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AreaGeneradora model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $are_id Are ID
     * @return AreaGeneradora the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($are_id)
    {
        if (($model = AreaGeneradora::findOne(['are_id' => $are_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
