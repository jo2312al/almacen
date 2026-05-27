<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ClaveProgramatica;

/**
 * ClaveProgramaticaSearch represents the model behind the search form of `app\models\ClaveProgramatica`.
 */
class ClaveProgramaticaSearch extends ClaveProgramatica
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cla_id'], 'integer'],
            [['cla_codigo', 'cla_descripcion'], 'safe'],
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
        $query = ClaveProgramatica::find();

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
            'cla_id' => $this->cla_id,
        ]);

        $query->andFilterWhere(['like', 'cla_codigo', $this->cla_codigo])
            ->andFilterWhere(['like', 'cla_descripcion', $this->cla_descripcion]);

        return $dataProvider;
    }
}
