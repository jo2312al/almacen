<?php

namespace app\controllers;

use Yii;
use app\models\Archivo;
use app\models\ArchivoSearch;
use app\services\PdfProcessorService; // Servicio IA
use app\services\ArchivoStorageService; // Servicio Archivos
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ArchivoController implementa las acciones CRUD para el modelo Archivo.
 */
class ArchivoController extends Controller
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
     * Deshabilitar CSRF para la API de Python o AJAX
     */
    public function beforeAction($action)
    {
        if (in_array($action->id, ['process-pdf', 'get-codigos'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Acción 1: Procesar PDF con IA (Usa PdfProcessorService)
     */
    public function actionProcessPdf()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $pdfFile = UploadedFile::getInstanceByName('pdfFile');
        
        if (!$pdfFile) {
            return ['status' => 'error', 'message' => 'No se recibió ningún archivo PDF.'];
        }

        $service = new PdfProcessorService();
        return $service->processPdf($pdfFile);
    }

    /**
     * Acción 2: Crear y Guardar (Usa ArchivoStorageService)
     */
    public function actionCreate()
    {
        $model = new Archivo();
        $model->scenario = 'create';

        if ($this->request->isPost) {
            // 1. Cargar datos del formulario
            $model->load($this->request->post());
            
            // 2. Obtener el archivo del formulario
            $fileInstance = UploadedFile::getInstance($model, 'file');

            // 3. Llamar al servicio de guardado
            $storageService = new ArchivoStorageService();
            $result = $storageService->saveArchivo($model, $fileInstance);

            if ($result['success']) {
                // ÉXITO
                Yii::$app->session->setFlash('success', $result['message']);
                return $this->redirect(['view', 'arc_id' => $result['id']]);
            } else {
                // ERROR
                Yii::$app->session->setFlash('error', $result['message']);
                
                // Si hay errores específicos de campos (ej. matrícula vacía), los asignamos al modelo
                // para que se muestren en rojo en el formulario.
                if (isset($result['errors'])) {
                    $model->addErrors($result['errors']);
                }
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Acción 3: Descargar Archivo
     */
    public function actionDownload($id)
    {
        $storageService = new ArchivoStorageService();
        $fileData = $storageService->getDownloadPath($id);

        if ($fileData) {
            return Yii::$app->response->sendFile($fileData['path'], $fileData['filename']);
        }

        throw new NotFoundHttpException('El archivo físico no fue encontrado en el servidor.');
    }

    /**
     * Acción 4: Eliminar Archivo
     */
    public function actionDelete($arc_id)
    {
        $storageService = new ArchivoStorageService();
        $storageService->deleteArchivo($arc_id);

        return $this->redirect(['index']);
    }

    // --- ACCIONES ESTÁNDAR (Index, View, Update) ---

    public function actionIndex()
    {
        $searchModel = new ArchivoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($arc_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($arc_id),
        ]);
    }

    public function actionUpdate($arc_id)
    {
        $model = $this->findModel($arc_id);
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'arc_id' => $model->arc_id]);
        }
        return $this->render('update', ['model' => $model]);
    }

    protected function findModel($arc_id)
    {
        if (($model = Archivo::findOne(['arc_id' => $arc_id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}