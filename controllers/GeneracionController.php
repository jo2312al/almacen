<?php

namespace app\controllers;

use app\models\Generacion;
use app\models\GeneracionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GeneracionController implements the CRUD actions for Generacion model.
 */
class GeneracionController extends Controller
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
     * Lists all Generacion models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GeneracionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Generacion model.
     * @param int $gen_id Gen ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($gen_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($gen_id),
        ]);
    }

    /**
     * Creates a new Generacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Generacion();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'gen_id' => $model->gen_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Generacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $gen_id Gen ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($gen_id)
    {
        $model = $this->findModel($gen_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'gen_id' => $model->gen_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Generacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $gen_id Gen ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($gen_id)
    {
        $this->findModel($gen_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Generacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $gen_id Gen ID
     * @return Generacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($gen_id)
    {
        if (($model = Generacion::findOne(['gen_id' => $gen_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
