<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AreaGeneradora;

/**
 * AreaGeneradoraSearch represents the model behind the search form of `app\models\AreaGeneradora`.
 */
class AreaGeneradoraSearch extends AreaGeneradora
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['are_id'], 'integer'],
            [['are_codigo', 'are_descripcion'], 'safe'],
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
        $query = AreaGeneradora::find();

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
            'are_id' => $this->are_id,
        ]);

        $query->andFilterWhere(['like', 'are_codigo', $this->are_codigo])
            ->andFilterWhere(['like', 'are_descripcion', $this->are_descripcion]);

        return $dataProvider;
    }
}
