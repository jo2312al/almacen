<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SeccionSerie;

/**
 * SeccionSerieSearch represents the model behind the search form of `app\models\SeccionSerie`.
 */
class SeccionSerieSearch extends SeccionSerie
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sec_id'], 'integer'],
            [['sec_codigo', 'sec_descripcion'], 'safe'],
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
        $query = SeccionSerie::find();

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
            'sec_id' => $this->sec_id,
        ]);

        $query->andFilterWhere(['like', 'sec_codigo', $this->sec_codigo])
            ->andFilterWhere(['like', 'sec_descripcion', $this->sec_descripcion]);

        return $dataProvider;
    }
}
