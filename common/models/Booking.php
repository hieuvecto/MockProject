<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Booking".
 *
 * @property int $booking_id
 * @property int $user_id
 * @property int $sub_pitch_id
 * @property string $book_day
 * @property string $start_time
 * @property string $end_time
 * @property string $message
 * @property string $is_verified
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property SubPitch $subPitch
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'sub_pitch_id', 'book_day', 'start_time', 'end_time', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'sub_pitch_id', 'created_at', 'updated_at'], 'integer'],
            [['book_day', 'start_time', 'end_time'], 'safe'],
            [['message'], 'string'],
            [['is_verified'], 'string', 'max' => 1],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['sub_pitch_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubPitch::className(), 'targetAttribute' => ['sub_pitch_id' => 'sub_pitch_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'booking_id' => 'Booking ID',
            'user_id' => 'User ID',
            'sub_pitch_id' => 'Sub Pitch ID',
            'book_day' => 'Book Day',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'message' => 'Message',
            'is_verified' => 'Is Verified',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubPitch()
    {
        return $this->hasOne(SubPitch::className(), ['sub_pitch_id' => 'sub_pitch_id']);
    }
}
