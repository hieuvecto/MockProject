<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Pitch;
use common\models\SubPitch; 
use yii\db\Expression;

/**
 * PitchSearch represents the model behind the search form of `common\models\Pitch`.
 */
class PitchSearch extends Pitch
{   
    public $size;
    public $keyword;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pitch_id', 'owner_id', 'created_at', 'updated_at', 'size'], 'integer'],
            [['name', 'description', 'city', 'district', 'address', 'phone_number', 'keyword'], 'safe'],
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
        $query = Pitch::find();

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
            'pitch_id' => $this->pitch_id,
            'owner_id' => $this->owner_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        $subQuery = SubPitch::find()->select('pitch_id')
            ->andFilterWhere([
                'pitch_id' => new Expression('`Pitch`.`pitch_id`'),
                'size' => $this->size,
            ]);

        if ($this->size)
            $query->andFilterWhere(['in', 'pitch_id', $subQuery]);

        Yii::info($this->keyword, 'Info keyword');
        if (trim($this->keyword) !== '')
            $query->andFilterWhere(['or', [
                    'like', 'name', $this->keyword
                ],
                [
                    'like', 'address', $this->keyword
                ]
            ]);

        return $dataProvider;
    }
}
