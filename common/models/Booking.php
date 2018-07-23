<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\SubPitch;

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
    public $book_range;
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
            [['user_id', 'sub_pitch_id', 'book_day', 'start_time'], 'required'],
            [['user_id', 'sub_pitch_id', 'created_at', 'updated_at', 'book_range'], 'integer'],
            [['book_day', 'start_time', 'end_time'], 'safe'],
            [['message'], 'string'],
            [['is_verified'], 'string', 'max' => 1],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'user_id']],
            [['sub_pitch_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubPitch::className(), 'targetAttribute' => ['sub_pitch_id' => 'sub_pitch_id']],
            ['book_day', 'validateBookDay'],
            ['start_time', 'validateTime'],
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
            'book_range' => 'Book Range',
        ];
    }

    public function validateBookDay($attribute, $params, $validator)
    {
        $book_day = new \DateTime($this->$attribute);
        $now = new \DateTime('NOW');

        if ($book_day < $now &&
            (int) $book_day->format('d') < (int) $now->format('d') ) 
            $validator->addError($this, $attribute, 'Ngày đặt sân phải sau ngày hiện tại.');
        $book_time = new \DateTime($this->book_day . 'T' . $this->start_time);
        $book_time = $book_time->modify('+5 hour');
        Yii::info($book_time, 'Debug book time');
        Yii::info($now, 'Debug now');
        if ($book_time < $now)
            $validator->addError($this, 'start_time', 'Thời gian đặt sân phải sau thời gian hiện tại 5 giờ.');

    }

    public function validateTime($attribute, $params, $validator)
    {   
        if (($subPitch = SubPitch::findOne($this->sub_pitch_id)) === null)
            throw new NotFoundHttpException('The requested sub pitch does not exist.');

        $start_time = new \DateTime($this->start_time);
        $this->end_time = Booking::computeEndTime($this->start_time, $this->book_range);
        $end_time = new \DateTime($this->end_time);

        $pitch_start_time = new \DateTime($subPitch->start_time);
        $pitch_end_time = new \DateTime($subPitch->end_time);

        if ($start_time < $pitch_start_time)
            $validator->addError($this, $attribute, 'Thời gian đặt phải nằm trong khung giờ mở cửa.');
        if ($end_time > $pitch_end_time || $end_time < $start_time)
            $validator->addError($this, 'book_range', 'Thời gian đặt phải nằm trong khung giờ mở cửa.');

        $escape_start_time = addslashes($this->start_time);
        $escape_end_time = addslashes($this->end_time);

        $queryStr = "
            SELECT * FROM `Booking` WHERE NOT (
            (`start_time` >= CAST('$escape_end_time' AS time)) OR 
            (`end_time` <= CAST('$escape_start_time' AS time))
        ) AND (`sub_pitch_id`=$subPitch->sub_pitch_id)
          AND (`is_verified`= 1)  ";

        if ($this->booking_id)
            $queryStr = $queryStr . "AND NOT (`booking_id`=$this->booking_id)";

        $exist = Booking::findBySql($queryStr)->exists();

        if ($exist)
        {
            $validator->addError($this, 'start_time', 'Thời gian đặt sân chồng lên thời gian đặt sân khác.');
            $validator->addError($this, 'book_range', 'Thời gian đặt sân chồng lên thời gian đặt sân khác.');
        }

        $this->total_price = Booking::diffByHours($this->end_time, $this->start_time) * $subPitch->price_per_hour;
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

    static public function diffByHours($timestr, $timestr2)
    {
        $time = new \DateTime($timestr);
        $time2 = new \DateTime($timestr2);

        $diff = date_diff($time, $time2);

        return (float) $diff->h + ((float) $diff->i)/60;
    }

    static public function computeEndTime($start_time, $book_range) 
    {   
        $minutes = (int) (((float) $book_range/10) * 60);
        $time = strtotime($start_time);
        $time = strtotime('+' . $minutes . ' minutes', $time);
    
        return date('H:i:s', $time);
    }
}