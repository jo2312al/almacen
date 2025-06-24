<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Caja;

/**
 * CajaSearch represents the model behind the search form of `app\models\Caja`.
 */
class CajaSearch extends Caja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caj_id', 'caj_anaquel_id', 'caj_nivel_id'], 'integer'],
            [['caj_codigo'], 'safe'],
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
        $query = Caja::find();

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
            'caj_id' => $this->caj_id,
            'caj_anaquel_id' => $this->caj_anaquel_id,
            'caj_nivel_id' => $this->caj_nivel_id,
        ]);

        $query->andFilterWhere(['like', 'caj_codigo', $this->caj_codigo]);

        return $dataProvider;
    }
}
