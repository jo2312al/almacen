<?php

namespace app\controllers;

use Yii;
use app\models\Archivo;
use app\models\ArchivoSearch;
use app\models\Alumno;
use app\models\Carrera;
use app\models\Fondo;
use app\models\ClaveProgramatica;
use app\models\AreaGeneradora;
use app\models\SeccionSerie;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use setasign\Fpdi\Fpdi;
use GuzzleHttp\Exception\ConnectException;

class ArchivoController extends Controller
{
    public function beforeAction($action)
    {
        if (in_array($action->id, ['process-pdf', 'get-codigos'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }
    
    public function behaviors()
    {
        return array_merge(parent::behaviors(),['verbs' => ['class' => VerbFilter::className(),'actions' => ['delete' => ['POST'],],],]);
    }

    public function actionIndex(){ $searchModel = new ArchivoSearch(); $dataProvider = $searchModel->search($this->request->queryParams); return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider,]); }
    public function actionView($arc_id){ return $this->render('view', ['model' => $this->findModel($arc_id),]); }

    public function actionCreate()
    {
        $model = new Archivo();
        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                if ($model->file) {
                    $alumno = Alumno::findOne($model->arc_alumno_id);
                    if ($alumno) {
                        $matricula = $alumno->alu_matricula;
                        $directoryPath = 'archivos/' . $matricula . '/';
                        FileHelper::createDirectory($directoryPath);
                        $tempOriginalPath = 'archivos/temp_' . time() . '.' . $model->file->extension;
                        $model->file->saveAs($tempOriginalPath);
                        $finalFilePath = $directoryPath . $model->arc_codigo . '.pdf';
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
                            Yii::error('FPDI Compression Error: ' . $e->getMessage(), 'app');
                            copy($tempOriginalPath, $finalFilePath);
                        }
                        unlink($tempOriginalPath);
                        $model->arc_ruta = $finalFilePath;
                    }
                }
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Archivo guardado correctamente.');
                    return $this->redirect(['index']);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        $alumnoModel = new Alumno();
        return $this->render('create', ['model' => $model, 'alumnoModel' => $alumnoModel]);
    }

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
            if (empty($matricula)) { return ['status' => 'error', 'message' => 'La API no pudo extraer una matrícula.']; }
            $alumnoExistente = Alumno::findOne(['alu_matricula' => $matricula]);
            if ($alumnoExistente) {
                return ['status' => 'ok', 'exists' => true, 'alumnoData' => $alumnoExistente->getAttributes()];
            } else {
                $apiData = $data['fields'];
                $processedData = [];
                $processedData['alu_matricula'] = $matricula;
                $processedData['alu_nombre'] = trim(str_replace(',', '', $apiData['alu_nombre']['value'] ?? ''));
                $processedData['alu_paterno'] = trim(str_replace(',', '', $apiData['alu_paterno']['value'] ?? ''));
                $processedData['alu_materno'] = trim(str_replace(',', '', $apiData['alu_materno']['value'] ?? ''));
                $carreraTexto = trim(str_replace(',', '', $apiData['alu_carrera']['value'] ?? ''));
                if ($carreraTexto) {
                    $carreraModel = Carrera::find()->where(['like', 'car_nombre', $carreraTexto])->one();
                    $processedData['alu_carrera_id'] = $carreraModel ? $carreraModel->car_id : null;
                }
                $servicioTexto = $apiData['alu_servicio']['value'] ?? '';
                if (preg_match('/(\d{4})/', $servicioTexto, $matches)) {
                    $processedData['alu_ingreso'] = $matches[1];
                }
                return ['status' => 'ok', 'exists' => false, 'processedData' => $processedData];
            }
        } catch (ConnectException $e) {
            return ['status' => 'error', 'message' => 'Error de Conexión: No se pudo comunicar con la API.'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error inesperado: ' . $e->getMessage()];
        }
    }

    /**
     * ===================================================================
     * ACCIÓN 'GetCodigos' CORREGIDA
     * - Ahora verifica si el modelo fue encontrado antes de acceder a sus propiedades.
     * - Esto previene el error fatal "Attempt to read property on null".
     * - Usa '00' como valor predeterminado si no hay selección.
     * ===================================================================
     */
    public function actionGetCodigos()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $ids = Yii::$app->request->post('ids');
        $codigos = [];

        $fondo = Fondo::findOne($ids['fondo_id']);
        $codigos['fondo'] = $fondo ? $fondo->fon_codigo : '00';

        $clave = ClaveProgramatica::findOne($ids['clave_id']);
        $codigos['clave'] = $clave ? $clave->cla_codigo : '00';

        $area = AreaGeneradora::findOne($ids['area_id']);
        $codigos['area'] = $area ? $area->are_codigo : '00';

        $seccion = SeccionSerie::findOne($ids['seccion_id']);
        $codigos['seccion'] = $seccion ? $seccion->sec_codigo : '00';

        return ['success' => true, 'codigos' => $codigos];
    }

    public function actionUpdate($arc_id){ $model = $this->findModel($arc_id); if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) { return $this->redirect(['view', 'arc_id' => $model->arc_id]); } return $this->render('update', ['model' => $model]); }
    public function actionDelete($arc_id){ $model = $this->findModel($arc_id); if ($model->arc_ruta && file_exists(Yii::getAlias('@webroot/') . $model->arc_ruta)) { unlink(Yii::getAlias('@webroot/') . $model->arc_ruta); } $model->delete(); return $this->redirect(['index']); }
    public function actionDownload($id){ $model = $this->findModel($id); $filePath = Yii::getAlias('@webroot/') . $model->arc_ruta; if (file_exists($filePath)) { return Yii::$app->response->sendFile($filePath, $model->arc_codigo . '.pdf'); } throw new \yii\web\NotFoundHttpException('El archivo físico no fue encontrado.'); }
    protected function findModel($arc_id){ if (($model = Archivo::findOne(['arc_id' => $arc_id])) !== null) { return $model; } throw new NotFoundHttpException('The requested page does not exist.'); }
}
