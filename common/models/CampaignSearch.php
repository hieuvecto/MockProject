<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Campaign;
use yii\db\Expression;

/**
 * BookingSearch represents the model behind the search form of `common\models\Booking`.
 */
class CampaignSearch extends Campaign
{   
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'owner_id', 'created_at', 'type', 'updated_at'], 'integer'],
            [['value'], 'double'],
            [['name', 'description', 'avatar_url', 'start_time', 'end_time'], 'safe'],
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
        $query = Campaign::find();

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
            'campaign_id' => $this->campaign_id,
            'owner_id' => $this->owner_id,
            'start_time' => $this->start_time ? new Expression("CAST('$this->start_time' AS datetime)") : '',
            'end_time' => $this->end_time ? new Expression("CAST('$this->end_time' AS datetime)") : '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'type'=> $this->type,
            'value'=> $this->value,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
