<?php

namespace app\controllers;

use app\models\AreaGeneradora;
use app\models\Alumno;
use app\models\Caja;
use app\models\CargaMasiva;
use app\models\CargaMasivaDetalle;
use app\models\CargaMasivaForm;
use app\models\ClaveProgramatica;
use app\models\Fondo;
use app\models\SeccionSerie;
use app\services\CargaMasivaService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class CargaMasivaController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ]);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CargaMasiva::find()->with('caja')->orderBy(['car_id' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {
        $model = new CargaMasivaForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->files = UploadedFile::getInstances($model, 'files');

            if ($model->validate() && !empty($model->files)) {
                $batch = (new CargaMasivaService())->process($model, $model->files);
                Yii::$app->session->setFlash('success', 'Carga masiva procesada.');
                return $this->redirect(['view', 'id' => $batch->car_id]);
            }

            if (empty($model->files)) {
                $model->addError('files', 'Selecciona al menos un PDF.');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'catalogs' => $this->catalogs(),
        ]);
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);
        $detailsProvider = new ActiveDataProvider([
            'query' => $model->getDetalles()->with(['archivo', 'alumno'])->orderBy(['det_id' => SORT_ASC]),
            'pagination' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'detailsProvider' => $detailsProvider,
        ]);
    }

    public function actionRevisar($id)
    {
        $detail = $this->findDetail($id);
        if ($detail->det_estado !== CargaMasivaDetalle::ESTADO_PENDIENTE) {
            Yii::$app->session->setFlash('info', 'Este registro ya no está pendiente.');
            return $this->redirect(['view', 'id' => $detail->det_carga_id]);
        }

        $model = new Alumno();
        $model->load($this->pendingAlumnoData($detail), '');

        if ($model->load(Yii::$app->request->post())) {
            $existing = Alumno::findOne(['alu_matricula' => $model->alu_matricula]);
            if ($existing) {
                $result = (new CargaMasivaService())->resolvePending($detail, $existing);
            } elseif ($model->save()) {
                $result = (new CargaMasivaService())->resolvePending($detail, $model);
            } else {
                return $this->render('revisar', [
                    'model' => $model,
                    'detail' => $detail,
                ]);
            }

            Yii::$app->session->setFlash($result['success'] ? 'success' : 'error', $result['message']);
            return $this->redirect(['view', 'id' => $detail->det_carga_id]);
        }

        return $this->render('revisar', [
            'model' => $model,
            'detail' => $detail,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = CargaMasiva::findOne(['car_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La carga masiva solicitada no existe.');
    }

    protected function findDetail($id)
    {
        if (($model = CargaMasivaDetalle::findOne(['det_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('El detalle solicitado no existe.');
    }

    private function pendingAlumnoData(CargaMasivaDetalle $detail)
    {
        $data = json_decode($detail->det_datos_extraidos ?: '[]', true);
        return is_array($data) ? $data : [];
    }

    private function catalogs()
    {
        return [
            'cajas' => Caja::find()->orderBy('caj_codigo')->all(),
            'fondos' => Fondo::find()->orderBy('fon_codigo')->all(),
            'claves' => ClaveProgramatica::find()->orderBy('cla_codigo')->all(),
            'areas' => AreaGeneradora::find()->orderBy('are_codigo')->all(),
            'secciones' => SeccionSerie::find()->orderBy('sec_codigo')->all(),
        ];
    }
}
