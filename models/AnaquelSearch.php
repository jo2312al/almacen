<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Anaquel;

/**
 * AnaquelSearch represents the model behind the search form of `app\models\Anaquel`.
 */
class AnaquelSearch extends Anaquel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ana_id'], 'integer'],
            [['ana_nombre'], 'safe'],
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
        $query = Anaquel::find();

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
            'ana_id' => $this->ana_id,
        ]);

        $query->andFilterWhere(['like', 'ana_nombre', $this->ana_nombre]);

        return $dataProvider;
    }
}
