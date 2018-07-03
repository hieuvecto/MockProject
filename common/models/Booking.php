<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Pitch;

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
            [['user_id', 'sub_pitch_id', 'book_day', 'start_time', 'end_time'], 'required'],
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

    static public function separateTime($time)
    {
        $array = explode(':', $time);
        return [
            'hour' => (int)$array[0],
            'minute' => (int)$array[1],
            'second' => (int) $array[2],
        ];
    }
    static public function convertTimeToHour($time)
    {
        $array = explode(':', $time);
        return ((float)$array[0]) + ((float)$array[1])/60;
    }

    static public function subtractTime($time, $time1)
    {
        $timeFloat = Booking::convertTimeToHour($time);
        $time1Float = Booking::convertTimeToHour($time1);

        return $timeFloat - $time1Float;
    }

    public function validateTime($subPitch) 
    {   
        if (!isset($subPitch) && ($subPitch = Pitch::findOne($id)) === null)
            throw new NotFoundHttpException('The requested sub pitch does not exist.');
        $start_time = Booking::convertTimeToHour($this->start_time);
        $end_time = Booking::convertTimeToHour($this->end_time);

        $pitch_start_time = Booking::convertTimeToHour($subPitch->start_time);
        $pitch_end_time = Booking::convertTimeToHour($subPitch->end_time);
        
        if ($start_time > $end_time) 
        {
            $this->addError('start_time', 'Start Time must be lower than End Time.');
            return;
        }

        if ($start_time < $pitch_start_time)
            $this->addError('start_time', 'Start Time must be higher than Pitch Start Time.');
        if ($end_time > $pitch_end_time)
            $this->addError('end_time', 'End Time must be lower than Pitch End Time.');
    }

    public function save( $runValidation = true, $attributeNames = null)
    {
        if ($this->hasErrors())
            return false;
        else 
            return parent::save();
    }
}
