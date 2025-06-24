<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Generacion;

/**
 * GeneracionSearch represents the model behind the search form of `app\models\Generacion`.
 */
class GeneracionSearch extends Generacion
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gen_id'], 'integer'],
            [['gen_nombre'], 'safe'],
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
        $query = Generacion::find();

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
            'gen_id' => $this->gen_id,
        ]);

        $query->andFilterWhere(['like', 'gen_nombre', $this->gen_nombre]);

        return $dataProvider;
    }
}
