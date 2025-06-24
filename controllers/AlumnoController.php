<?php

namespace app\controllers;
use Yii;
use app\models\Alumno;
use app\models\AlumnoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlumnoController implements the CRUD actions for Alumno model.
 */
class AlumnoController extends Controller
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
                    ],
                ],
            ]
        );
        
    }

    /**
     * Lists all Alumno models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AlumnoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alumno model.
     * @param int $alu_id Alu ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($alu_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($alu_id),
        ]);
    }

    /**
     * Creates a new Alumno model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Alumno();
    
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // Redirige a la acción `create` de otro modelo, pasando el `alu_id` como parámetro
                return $this->redirect(['/archivo/create', 'alu_id' => $model->alu_id]);
            }
        } else {
            $model->loadDefaultValues();
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionGenerarQr()
    {
        $qr = Yii::$app->get('qr');
    
        // Obtiene la URL actual de la página
        $qrText = Yii::$app->request->absoluteUrl;
    
        $fileName = 'qr_actual.png';
    
        $qrImage = $qr
            ->setText($qrText)
            ->setSize(300)
            ->setErrorCorrectionLevel('H')
            ->writeString();
    
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'image/png');
        Yii::$app->response->headers->add('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    
        return $qrImage;
    }
    
    /**
     * Updates an existing Alumno model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $alu_id Alu ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($alu_id)
    {
        $model = $this->findModel($alu_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'alu_id' => $model->alu_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Alumno model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $alu_id Alu ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($alu_id)
    {
        $this->findModel($alu_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Alumno model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $alu_id Alu ID
     * @return Alumno the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($alu_id)
    {
        if (($model = Alumno::findOne(['alu_id' => $alu_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
