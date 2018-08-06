<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Booking;
use common\models\SubPitch;
use yii\db\Expression;

/**
 * BookingSearch represents the model behind the search form of `common\models\Booking`.
 */
class BookingSearch extends Booking
{   
    public $sub_pitch_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking_id', 'user_id', 'sub_pitch_id', 'created_at', 'updated_at'], 'integer'],
            [['book_day', 'start_time', 'end_time', 'message', 'is_verified', 'is_paid'], 'safe'],
            [['sub_pitch_name'], 'string'],
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
        $query = Booking::find();

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
            'booking_id' => $this->booking_id,
            'user_id' => $this->user_id,
            'sub_pitch_id' => $this->sub_pitch_id,
            'book_day' => $this->book_day ? new Expression("CAST('$this->book_day' AS date)") : '',
            'start_time' => $this->start_time ? new Expression("CAST('$this->start_time' AS time)") : '',
            'end_time' => $this->end_time ? new Expression("CAST('$this->end_time' AS time)") : '',
            'created_at' => $this->created_at ? new Expression("CAST('$this->created_at' AS datetime)") : '',
            'updated_at' => $this->updated_at ? new Expression("CAST('$this->updated_at' AS datetime)") : '',
            'is_verified'=> $this->is_verified,
            'is_paid'=> $this->is_paid,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        $subQuery = SubPitch::find()->select('sub_pitch_id')
            ->andFilterWhere([
                'sub_pitch_id' => new Expression('`Booking`.`sub_pitch_id`'),
            ])
            ->andFilterWhere([
                'like', 'name', $this->sub_pitch_name,
            ]);

        if (trim($this->sub_pitch_name) !== '')
            $query->andFilterWhere(['in', 'sub_pitch_id', $subQuery]);

        return $dataProvider;
    }
}
