<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Booking;
use common\helpers\Utils;
use yii\web\UploadedFile;

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
     * @var UploadedFile
     */
    public $imageFile;

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
            [['description', 'avatar_url'], 'string'],
            [['pitch_id', 'price_per_hour', 'created_at', 'updated_at'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['status'], 'string', 'max' => 1],
            [['currency'], 'string', 'max' => 3],
            [['pitch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pitch::className(), 'targetAttribute' => ['pitch_id' => 'pitch_id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ['start_time', 'validateTime'],
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
            'avatar_url' => 'Avatar Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'image_file' => 'Image File',
        ];
    }

    public function validateTime($attribute, $params, $validator)
    {
        $start_time = new \DateTime($this->$attribute);
        $end_time = new \DateTime($this->end_time);

        $hoursDiff = Booking::diffByHours($this->$attribute, $this->end_time);
        if ($start_time > $end_time)
            $validator->addError($this, $attribute, 'Start time must be lower than end time.');
        elseif ($hoursDiff < 5.0) 
        {
            $validator->addError($this, $attribute, 'The difference between start time and end time must be highter than 5 hours.');
            $validator->addError($this, 'end_time', 'The difference between start time and end time must be highter than 5 hours.');
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings($params = null)
    {   
        if (!$params)
            return $this->hasMany(Booking::className(), ['sub_pitch_id' => 'sub_pitch_id']);
        return $this->hasMany(Booking::className(), ['sub_pitch_id' => 'sub_pitch_id',])
                    ->andFilterWhere($params);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPitch()
    {
        return $this->hasOne(Pitch::className(), ['pitch_id' => 'pitch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignSubPitches()
    {
        return $this->hasMany(CampaignSubPitch::className(), ['sub_pitch_id' => 'sub_pitch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaigns($params = null)
    {   
        $query = $this->hasMany(Campaign::className(), ['campaign_id' => 'campaign_id']);
        if (isset($params)) 
        {   
            if (is_array($params))
            {
                foreach ($params as $value) {
                    $query->andFilterWhere($value);
                }
            }
            else 
                $query->andFilterWhere($params);
        }

        $query->viaTable('CampaignSubPitch', ['sub_pitch_id' => 'sub_pitch_id']);

        return $query;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        Booking::deleteAll(['sub_pitch_id' => $this->sub_pitch_id ]);
        
        if (isset($this->avatar_url))
            return unlink(Yii::getAlias('@webroot') . '/'. $this->avatar_url);
        return true;
    }

    public function save($useParent = true, $runValidation = true, $attributeNames = null)
    {   
        if (!$useParent) 
        {
            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
            if (isset($this->imageFile))
            {
                $path = 'uploads/' . uniqid('SubPitchImage', true) . '.' . $this->imageFile->extension;
                $old_avatar_url = $this->avatar_url;

                if ($this->upload($path))
                {   
                    if (isset($old_avatar_url))
                        unlink(Yii::getAlias('@webroot') . '/'. $old_avatar_url);

                    $this->avatar_url = $path;
                }
                else
                    $this->addError('image_file', 'There was an error uploading your image.');
            } 
        }
        
        return parent::save($runValidation, [ 
            'sub_pitch_id',
            'name',
            'description',
            'status',
            'pitch_id',
            'start_time',
            'end_time',
            'price_per_hour',
            'currency',
            'avatar_url',
            'created_at',
            'updated_at',
        ]);
    }

    public function upload($path)
    {
        if ($this->validate('image_file')) {
            return $this->imageFile->saveAs($path);
        } else {
            return false;
        }
    }

    public function getEvents()
    {
        $verifiedBookings = $this->getBookings(['is_verified' => 1])->all();
        $array = [];

        foreach ($verifiedBookings as $booking) {
            $array[] = [
                'start' => $booking->book_day . 'T' . $booking->start_time,
                'end' => $booking->book_day . 'T' . $booking->end_time,
                'color' => '#488214',
            ];
        }
        Yii::info($array, 'Debug get events');
        return $array;
    }

    static public function weekRevenue($sub_pitch_id)
    {   
        $week = Utils::getWeekLabels('Y-m-d', false);
        $result = [0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0];
        
        $connection = Yii::$app->getDb();
        
        foreach ($week as $key => $day) {
            $command = $connection->createCommand( "
                SELECT `total_price` FROM `Booking` WHERE 
                    (`sub_pitch_id`=$sub_pitch_id)
                AND (`book_day` = CAST('$day' AS date))
                AND (`is_verified`= 1)
                ");

            $rs = $command->queryAll();

            foreach ($rs as $total_price_row) {
                $total_price = $total_price_row['total_price'];
                $result[$key] += (float) $total_price;
            }

        }
       
        return $result;

    }
}
