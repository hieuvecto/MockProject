<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Booking;
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings($params)
    {   
        Yii::info('In get bookings');
        if (!isset($params))
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
}
