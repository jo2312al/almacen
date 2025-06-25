<?php

namespace app\controllers;

use yii\helpers\Url;

use app\views\caja\barcodeGenerator;
use app\models\Caja;
use app\models\CajaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\Json;
use yii;
use app\models\Anaquel; // Importa la clase Anaquel
use app\models\Nivelalmacenamiento; // Importa la clase Nivelalmacenamiento
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
     *
     * @return string
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
     * @param int $caj_id Caj ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($caj_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($caj_id),
        ]);
    }

    /**
     * Creates a new Caja model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Caja();
    
        if ($model->load(Yii::$app->request->post())) {
            // Extraer datos del modelo
            $anaquelId = $model->caj_anaquel_id;
            $nivelId = $model->caj_nivel_id;
    
            Yii::debug("Anaquel ID: $anaquelId, Nivel ID: $nivelId", __METHOD__);
    
            // Validar que ambos valores existan
            if ($anaquelId && $nivelId) {
                // Generar el código
                $codigo = $this->generarCodigoCaja($anaquelId, $nivelId);
                Yii::debug("Código generado: $codigo", __METHOD__);
    
                if ($codigo) {
                    $model->caj_codigo = $codigo;
    
                    // Intentar guardar el modelo y mostrar errores si falló
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', "Caja creada con código: $codigo");
                        return $this->redirect(['view', 'caj_id' => $model->caj_id]);
                    } else {
                        Yii::debug("Error al guardar el modelo: " . json_encode($model->errors), __METHOD__);
                        Yii::$app->session->setFlash('error', 'Error al guardar la caja. Verifique los datos.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'No se pudo generar el código.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Debe seleccionar un Anaquel y un Nivel.');
            }
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
  public function actionGenerarQr($caj_id)
    {
        try {
            $qr = Yii::$app->get('qr');

            // Construye la URL para la acción caja/view con el caj_id
            $qrText = Url::to(['caja/view', 'caj_id' => $caj_id], true);

            $fileName = 'qr_caja_' . $caj_id . '.png';

            // Genera la imagen del QR
            $qrImage = $qr
                ->setText($qrText)
                ->setSize(300)
                ->setErrorCorrectionLevel('H')
                ->writeString();

            // Configura la respuesta para descargar el archivo
            Yii::$app->response->format = Response::FORMAT_RAW;
            Yii::$app->response->headers
                ->add('Content-Type', 'image/png')
                ->add('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->add('Cache-Control', 'no-cache');

            return $qrImage;
        } catch (\Exception $e) {
            Yii::error('Error generando QR: ' . $e->getMessage(), __METHOD__);
            throw new \yii\web\HttpException(500, 'No se pudo generar el código QR.');
        }
    }
    

    private function generarCodigoCaja($anaquelId, $nivelId)
    {
        $anaquel = Anaquel::findOne($anaquelId);
        $nivel = Nivelalmacenamiento::findOne($nivelId);
    
        if (!$anaquel || !$nivel) {
            Yii::debug("Anaquel o Nivel no encontrados", __METHOD__);
            return null;
        }
    
        // Obtener la primera letra del nombre del nivel
        $primerLetraNivel = strtoupper(substr($nivel->niv_nombre, 0, 1));
    
        // Contar cajas existentes en ese nivel y anaquel
        $count = Caja::find()
            ->where(['caj_anaquel_id' => $anaquelId, 'caj_nivel_id' => $nivelId])
            ->count();
    
        Yii::debug("Número de cajas existentes en el nivel y anaquel: $count", __METHOD__);
    
        // Generar el número único
        $contador = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    
        // Generar el código final
        $codigo = "AC" . str_pad($anaquel->ana_id, 2, '0', STR_PAD_LEFT) . 
                  $primerLetraNivel . 
                  $contador;
    
        Yii::debug("Código generado: $codigo", __METHOD__);
    
        return $codigo;
    }

    /**
     * Deletes an existing Caja model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $caj_id Caj ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($caj_id)
    {
        $this->findModel($caj_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Caja model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $caj_id Caj ID
     * @return Caja the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($caj_id)
    {
        if (($model = Caja::findOne(['caj_id' => $caj_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
    /*
    public function actionGenerarCodigo()
    {
        $anaquelId = Yii::$app->request->get('ana_id');
        $nivelId = Yii::$app->request->get('niv_id');
    
        // Verifica que los IDs no estén vacíos
        if (!$anaquelId || !$nivelId) {
            return 'Error: Parámetros insuficientes';
        }
    
        // Busca los objetos Anaquel y Nivelalmacenamiento
        $anaquel = Anaquel::findOne($anaquelId);
        $nivel = Nivelalmacenamiento::findOne($nivelId);
    
        if (!$anaquel || !$nivel) {
            return 'Error: Anaquel o Nivel no encontrados';
        }
    
        // Generar el código en el formato requerido
        $codigoGenerado = `${anaquel->ana_id.substring(0, 2).toLowerCase()}--${nivel->niv_abreviatura.toLowerCase()} ${('0000' + contador).slice(-4)}`;
    
        // Incrementar el contador para la próxima vez
        $contador++;
    
        return $codigoGenerado;
    }
    
}
}
*/