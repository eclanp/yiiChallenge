<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Supplier;

/**
 * SupplierSearch represents the model behind the search form of `app\models\Supplier`.
 */
class SupplierSearch extends Supplier
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'safe'],
            [['name', 'code', 't_status'], 'safe'],
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
        $query = Supplier::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->applyParams($query);

        return $dataProvider;
    }

    public function buildQuery($params) {
        $query = Supplier::find();
        $this->load($params);
        if ($this->validate()) {
            $this->applyParams($query);
        }

        if (isset($params['ids']) && $params['ids'] != '') {
            $ids = explode(',', $params['ids']);
            if (count($ids)) {
                $query->andFilterWhere(['in', 'id', $ids]);
            }
        }

        return $query;
    }

    public function applyParams($query)
    {
        // grid filtering conditions
        if ($this->id != '') {
            $condition = '';
            if (substr($this->id, 0, 2) == '>=') {
                $condition = '>=';
            } else if (substr($this->id, 0, 2) == '<=') {
                $condition = '<=';
            } else if (substr($this->id, 0, 1) == '>') {
                $condition = '>';
            } else if (substr($this->id, 0, 1) == '<') {
                $condition = '<';
            } else {
                $condition = '=';
            }

            $id = (int)str_replace($condition, '', $this->id);
            if ($id > 0) {
                $query->andFilterWhere([$condition, 'id', $id]);
            }
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 't_status', $this->t_status]);
    }
}
