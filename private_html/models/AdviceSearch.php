<?php

namespace app\models;

use app\components\Helper;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserRequestSearch represents the model behind the search form of `app\models\UserRequest`.
 */
class AdviceSearch extends Advice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'userID', 'status'], 'integer'],
            [['type'], 'number'],
            [['name'], 'safe'],
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
        $query = Advice::find();
        $query->andWhere(['type' => Advice::$typeName]);

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
            'id' => $this->id,
            'userID' => $this->userID,
            'type' => $this->type,
            'created' => $this->created,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['REGEXP', 'name', Helper::persian2Arabic($this->name)])
            ->orFilterWhere(['REGEXP', static::columnGetString('family'), Helper::persian2Arabic($this->name)]);

        $query->orderBy(['status' => SORT_ASC,'id' => SORT_DESC]);

        return $dataProvider;
    }
}
