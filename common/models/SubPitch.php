<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "SubPitch".
 *
 * @property int $sub_pitch_id
 * @property string $name
 * @property string $description
 * @property string $status
 * @property int $pitch_id
 * @property string $start_time
 * @property string $end_time
 * @property int $price_per_hour
 * @property string $currency
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Booking[] $bookings
 * @property Pitch $pitch
 */
class SubPitch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'SubPitch';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'start_time', 'end_time', 'price_per_hour'], 'required'],
            [['description'], 'string'],
            [['pitch_id', 'price_per_hour', 'created_at', 'updated_at'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['status'], 'string', 'max' => 1],
            [['currency'], 'string', 'max' => 3],
            [['pitch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pitch::className(), 'targetAttribute' => ['pitch_id' => 'pitch_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sub_pitch_id' => 'Sub Pitch ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'pitch_id' => 'Pitch ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'price_per_hour' => 'Price Per Hour',
            'currency' => 'Currency',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['sub_pitch_id' => 'sub_pitch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPitch()
    {
        return $this->hasOne(Pitch::className(), ['pitch_id' => 'pitch_id']);
    }
}
