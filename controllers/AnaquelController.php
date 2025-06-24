<?php

namespace app\controllers;

use yii;
use app\models\Anaquel;
use app\models\AnaquelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AnaquelController implements the CRUD actions for Anaquel model.
 */
class AnaquelController extends Controller
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
                         'create' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Anaquel models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AnaquelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Anaquel model.
     * @param int $ana_id Ana ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ana_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($ana_id),
        ]);
    }

    /**
     * Creates a new Anaquel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
   public function actionCreate()
{
    // Asegurarse de que la petición sea por POST como se definió en 'verbs'
    if (Yii::$app->request->isPost) {
        
        $model = new Anaquel();

        // --- INICIO DE TU LÓGICA DE NEGOCIO (INTACTA) ---
        // Buscar el último nombre de anaquel en la base de datos y obtener el siguiente
        $lastAnaquel = Anaquel::find()->select('ana_nombre')->orderBy(['ana_nombre' => SORT_DESC])->limit(1)->one();
        
        if ($lastAnaquel) {
            // Extraer el número del último nombre y sumarle 1
            preg_match('/AA(\d+)/', $lastAnaquel->ana_nombre, $matches);
            if (isset($matches[1])) {
                $nextNumber = str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT);
                $model->ana_nombre = 'AA' . $nextNumber;
            } else {
                // Si no se encuentra un formato válido, comenzar desde 0001
                $model->ana_nombre = 'AA0001';
            }
        } else {
            // Si no hay registros, comenzar desde 0001
            $model->ana_nombre = 'AA0001';
        }
        // --- FIN DE TU LÓGICA DE NEGOCIO ---

        // Configurar la respuesta para que sea en formato JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Guardar el nuevo modelo
        if ($model->save()) {
            // Si el guardado es exitoso, devolver JSON de éxito
            return [
                'success' => true,
                'message' => 'El anaquel se ha creado exitosamente.',
                'data' => [
                    'ana_id' => $model->ana_id,
                    'ana_nombre' => $model->ana_nombre,
                ]
            ];
        } else {
            // Si hay un error al guardar, devolver JSON de error
            return [
                'success' => false,
                'message' => 'Ocurrió un error al intentar guardar el anaquel.',
                'errors' => $model->getErrors(),
            ];
        }
    }

    // Si el método no es POST, lanzar una excepción.
    throw new \yii\web\MethodNotAllowedHttpException();
}

    

    /**
     * Updates an existing Anaquel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ana_id Ana ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($ana_id)
    {
        $model = $this->findModel($ana_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'ana_id' => $model->ana_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Anaquel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ana_id Ana ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ana_id)
    {
        $this->findModel($ana_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Anaquel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ana_id Ana ID
     * @return Anaquel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ana_id)
    {
        if (($model = Anaquel::findOne(['ana_id' => $ana_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
