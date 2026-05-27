<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Alumno;

/**
 * AlumnoSearch represents the model behind the search form of `app\models\Alumno`.
 */
class AlumnoSearch extends Alumno
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['alu_id', 'alu_generacion_id', 'alu_servicio_id', 'alu_carrera_id'], 'integer'],
            [['alu_matricula', 'alu_nombre', 'alu_paterno', 'alu_materno', 'alu_ingreso'], 'safe'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Alumno::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'alu_id' => $this->alu_id,
            'alu_generacion_id' => $this->alu_generacion_id,
            'alu_ingreso' => $this->alu_ingreso,
            'alu_servicio_id' => $this->alu_servicio_id,
            'alu_carrera_id' => $this->alu_carrera_id,
        ]);

        $query->andFilterWhere(['like', 'alu_matricula', $this->alu_matricula])
            ->andFilterWhere(['like', 'alu_nombre', $this->alu_nombre])
            ->andFilterWhere(['like', 'alu_paterno', $this->alu_paterno])
            ->andFilterWhere(['like', 'alu_materno', $this->alu_materno]);

        return $dataProvider;
    }
}
