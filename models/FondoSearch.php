<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fondo;

/**
 * FondoSearch represents the model behind the search form of `app\models\Fondo`.
 */
class FondoSearch extends Fondo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fon_id'], 'integer'],
            [['fon_codigo', 'fon_descripcion'], 'safe'],
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
        $query = Fondo::find();

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
            'fon_id' => $this->fon_id,
        ]);

        $query->andFilterWhere(['like', 'fon_codigo', $this->fon_codigo])
            ->andFilterWhere(['like', 'fon_descripcion', $this->fon_descripcion]);

        return $dataProvider;
    }
}
