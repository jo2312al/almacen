<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Archivo;

/**
 * ArchivoSearch represents the model behind the search form of `app\models\Archivo`.
 */
class ArchivoSearch extends Archivo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['arc_id', 'arc_caja_id', 'arc_alumno_id', 'arc_fondo_id', 'arc_clave_programatica_id', 'arc_area_generadora_id', 'arc_seccion_serie_id'], 'integer'],
            [['arc_codigo', 'arc_nombre_documento', 'arc_ruta'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Archivo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'arc_id' => $this->arc_id,
            'arc_caja_id' => $this->arc_caja_id,
            'arc_alumno_id' => $this->arc_alumno_id,
            'arc_fondo_id' => $this->arc_fondo_id,
            'arc_clave_programatica_id' => $this->arc_clave_programatica_id,
            'arc_area_generadora_id' => $this->arc_area_generadora_id,
            'arc_seccion_serie_id' => $this->arc_seccion_serie_id,
        ]);

        $query->andFilterWhere(['like', 'arc_codigo', $this->arc_codigo])
            ->andFilterWhere(['like', 'arc_nombre_documento', $this->arc_nombre_documento])
            ->andFilterWhere(['like', 'arc_ruta', $this->arc_ruta]);

        return $dataProvider;
    }
}
