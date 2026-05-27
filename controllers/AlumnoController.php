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
        $request = Yii::$app->request;

        if ($request->isAjax) {
            // Maneja el envío del formulario desde el modal (petición POST) - GUARDAR
            if ($request->isPost) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if ($model->load($request->post()) && $model->save()) {
                    $nombreCompleto = method_exists($model, 'getNombreCompleto') ? $model->getNombreCompleto() : $model->alu_nombre;
                    return ['success' => true, 'id' => $model->alu_id, 'nombreCompleto' => $nombreCompleto, 'matricula' => $model->alu_matricula];
                } else {
                    // Si la validación falla (ej. matrícula duplicada), devolvemos el formulario con errores
                    return ['success' => false, 'formHtml' => $this->renderAjax('_form', ['model' => $model])];
                }
            }
            
            // Maneja la carga inicial del formulario en el modal (petición GET) - PRELLENADO
            // MODIFICACIÓN AQUÍ: Se eliminó el segundo parámetro '' para que lea el array 'Alumno'
            $model->load($request->get()); 
            
            return $this->renderAjax('_form', ['model' => $model]);
        }
        
        // Lógica para acceso directo a la URL (no AJAX)
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'alu_id' => $model->alu_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Busca un alumno por su ID y devuelve su información en formato JSON.
     */
    public function actionGetAlumnoInfo($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $alumno = $this->findModel($id);
            $nombreCompleto = method_exists($alumno, 'getNombreCompleto') ? $alumno->getNombreCompleto() : $alumno->alu_nombre;
            return ['success' => true, 'matricula' => $alumno->alu_matricula, 'nombre' => $nombreCompleto];
        } catch (NotFoundHttpException $e) {
            return ['success' => false, 'message' => 'El alumno con el ID proporcionado no fue encontrado.'];
        }
    }

    public function actionGenerarQr() { /* ... Tu código existente ... */ }
    public function actionUpdate($alu_id) { /* ... Tu código existente ... */ }
    public function actionDelete($alu_id) { /* ... Tu código existente ... */ }

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