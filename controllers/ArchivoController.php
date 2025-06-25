<?php

namespace app\controllers;

use Yii;
use app\models\Archivo;
use app\models\ArchivoSearch;
use app\models\Alumno;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
// Las librerías que Composer debe encontrar:
use setasign\Fpdi\Fpdi;
use GuzzleHttp\Exception\ConnectException;

/**
 * ArchivoController implements the CRUD actions for Archivo model.
 */
class ArchivoController extends Controller
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        // Deshabilitamos la validación CSRF solo para nuestra acción AJAX
        // para evitar problemas comunes.
        if ($action->id === 'process-pdf') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    public function behaviors()
    {
        return array_merge(parent::behaviors(),[
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [ 'delete' => ['POST'], ],
            ],
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new ArchivoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]);
    }

    public function actionView($arc_id)
    {
        return $this->render('view', ['model' => $this->findModel($arc_id),]);
    }

    /**
     * ===================================================================
     * ACCIÓN 'CREATE' MODIFICADA
     * - Maneja el guardado final del archivo.
     * - Crea la carpeta del alumno por su matrícula.
     * - Intenta "comprimir" el PDF usando FPDI.
     * ===================================================================
     */
    public function actionCreate()
    {
        $model = new Archivo();
        if ($this->request->isPost) {
            $model->load($this->request->post());
            // Usamos el atributo virtual 'file' del modelo.
            $model->file = UploadedFile::getInstance($model, 'file');

            // Validamos que el archivo y el modelo sean correctos.
            if ($model->file && $model->validate()) {
                $alumno = Alumno::findOne($model->arc_alumno_id);
                if ($alumno) {
                    $matricula = $alumno->alu_matricula;
                    // 1. Crear la carpeta con la matrícula del alumno
                    $directoryPath = 'archivos/' . $matricula . '/';
                    FileHelper::createDirectory($directoryPath);

                    // 2. Guardar el archivo original temporalmente
                    $tempOriginalPath = 'archivos/temp_' . time() . '.' . $model->file->extension;
                    $model->file->saveAs($tempOriginalPath);

                    // 3. Ruta final del archivo (usando el código generado)
                    $finalFilePath = $directoryPath . $model->arc_codigo . '.pdf';

                    // 4. Intentar "comprimir" el PDF reescribiéndolo
                    try {
                        $pdf = new Fpdi();
                        $pageCount = $pdf->setSourceFile($tempOriginalPath);
                        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                            $templateId = $pdf->importPage($pageNo);
                            $size = $pdf->getTemplateSize($templateId);
                            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                            $pdf->useTemplate($templateId);
                        }
                        $pdf->Output($finalFilePath, 'F');
                    } catch (\Exception $e) {
                        // Si FPDI falla, simplemente copiamos el archivo original sin comprimir
                        Yii::error('FPDI Compression Error: ' . $e->getMessage(), 'app');
                        copy($tempOriginalPath, $finalFilePath);
                    }
                    // 5. Borrar el archivo temporal
                    unlink($tempOriginalPath);
                    $model->arc_ruta = $finalFilePath;

                    // 6. Guardar el modelo Archivo en la base de datos
                    if ($model->save(false)) { // 'false' para no volver a validar
                        Yii::$app->session->setFlash('success', 'Archivo procesado y guardado correctamente.');
                        return $this->redirect(['index']);
                    }
                } else {
                    $model->addError('arc_alumno_id', 'Se debe seleccionar un alumno válido.');
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        $alumnoModel = new Alumno();
        return $this->render('create', ['model' => $model, 'alumnoModel' => $alumnoModel]);
    }

    /**
     * ===================================================================
     * NUEVA ACCIÓN: Process PDF
     * - Se comunica con la API de Python.
     * - Devuelve los datos para la revisión manual.
     * ===================================================================
     */
    public function actionProcessPdf()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $pdfFile = UploadedFile::getInstanceByName('pdfFile');
        if (!$pdfFile) { return ['status' => 'error', 'message' => 'No se recibió ningún archivo.']; }
        $apiEndpoint = 'http://127.0.0.1:5000/extract';

        try {
            $client = new \GuzzleHttp\Client(['timeout' => 120]);
            $apiResponse = $client->request('POST', $apiEndpoint, ['multipart' => [['name' => 'file', 'contents' => fopen($pdfFile->tempName, 'r'), 'filename' => $pdfFile->name]]]);
            $data = json_decode($apiResponse->getBody()->getContents(), true);
            if (json_last_error() !== JSON_ERROR_NONE) { throw new \Exception("La API no devolvió un JSON válido."); }

            $matricula = trim(str_replace(',', '', $data['fields']['alu_matricula']['value'] ?? ''));
            if (empty($matricula)) {
                 return ['status' => 'error', 'message' => 'La API no pudo extraer una matrícula del documento.'];
            }

            $alumnoExistente = Alumno::findOne(['alu_matricula' => $matricula]);

            if ($alumnoExistente) {
                // El alumno EXISTE. Devolvemos sus datos para la revisión manual.
                return ['status' => 'ok', 'exists' => true, 'alumnoData' => $alumnoExistente->getAttributes()];
            } else {
                // El alumno es NUEVO. Devolvemos los datos de la API para rellenar el formulario.
                return ['status' => 'ok', 'exists' => false, 'apiData' => $data['fields']];
            }
        } catch (ConnectException $e) {
            Yii::error("API Connection Error: " . $e->getMessage(), 'app');
            return ['status' => 'error', 'message' => 'Error de Conexión: No se pudo comunicar con el servicio de análisis. Verifique que la API de Python esté en ejecución.'];
        } catch (\Exception $e) {
            Yii::error("General API Error: " . $e->getMessage(), 'app');
            return ['status' => 'error', 'message' => 'Ocurrió un error inesperado al procesar el documento: ' . $e->getMessage()];
        }
    }

    public function actionUpdate($arc_id){ $model = $this->findModel($arc_id); if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) { return $this->redirect(['view', 'arc_id' => $model->arc_id]); } return $this->render('update', ['model' => $model]); }
    public function actionDelete($arc_id){ $model = $this->findModel($arc_id); if ($model->arc_ruta && file_exists(Yii::getAlias('@webroot/') . $model->arc_ruta)) { unlink(Yii::getAlias('@webroot/') . $model->arc_ruta); } $model->delete(); return $this->redirect(['index']); }
    public function actionDownload($id){ $model = $this->findModel($id); $filePath = Yii::getAlias('@webroot/') . $model->arc_ruta; if (file_exists($filePath)) { return Yii::$app->response->sendFile($filePath, $model->arc_codigo . '.pdf'); } throw new \yii\web\NotFoundHttpException('El archivo físico no fue encontrado.'); }
    protected function findModel($arc_id){ if (($model = Archivo::findOne(['arc_id' => $arc_id])) !== null) { return $model; } throw new NotFoundHttpException('The requested page does not exist.'); }
}
