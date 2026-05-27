<?php

namespace app\controllers;

use Yii;
use app\models\Caja;
use app\models\CajaSearch;
use app\services\CajaService; // <--- Importamos el servicio
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CajaController implements the CRUD actions for Caja model.
 */
class CajaController extends Controller
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
     * Lists all Caja models.
     */
    public function actionIndex()
    {
        $searchModel = new CajaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Caja model.
     */
    public function actionView($caj_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($caj_id),
        ]);
    }

    /**
     * Creates a new Caja model using CajaService logic.
     */
    public function actionCreate()
    {
        $model = new Caja();

        if ($this->request->isPost) {
            // Instanciamos el servicio
            $service = new CajaService();
            
            // Delegamos la creación
            $result = $service->createCaja($this->request->post());

            if ($result['success']) {
                Yii::$app->session->setFlash('success', $result['message']);
                return $this->redirect(['view', 'caj_id' => $result['model']->caj_id]);
            } else {
                Yii::$app->session->setFlash('error', $result['message']);
                $model = $result['model']; // Recuperamos el modelo con errores para mostrarlos
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Generates and downloads the QR code image.
     */
    public function actionGenerarQr($caj_id)
    {
        try {
            $service = new CajaService();
            $qrData = $service->generateQrImage($caj_id);

            // Configuramos la respuesta HTTP para descarga de archivo
            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers
                ->add('Content-Type', 'image/png')
                ->add('Content-Disposition', 'attachment; filename="' . $qrData['filename'] . '"')
                ->add('Cache-Control', 'no-cache');

            return $qrData['content'];

        } catch (\Exception $e) {
            throw new \yii\web\HttpException(500, 'No se pudo generar el código QR.');
        }
    }

    /**
     * Deletes an existing Caja model.
     */
    public function actionDelete($caj_id)
    {
        $this->findModel($caj_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Caja model based on its primary key value.
     */
    protected function findModel($caj_id)
    {
        if (($model = Caja::findOne(['caj_id' => $caj_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}