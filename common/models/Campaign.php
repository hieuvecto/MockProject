<?php

namespace common\models;

use common\helpers\Utils;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;
use yii\base\NotSupportedException;
use Yii;

/**
 * This is the model class for table "Campaign".
 *
 * @property int $campaign_id
 * @property string $name
 * @property string $description
 * @property int $owner_id
 * @property string $avatar_url
 * @property int $start_time
 * @property int $end_time
 * @property int $type
 * @property double $value
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Owner $owner
 * @property CampaignSubPitch[] $campaignSubPitches
 */
class Campaign extends \yii\db\ActiveRecord
{   
    /**
     * @var UploadedFile
     */
    public $image_file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Campaign';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'owner_id', 'start_time', 'end_time', 'value'], 'required'],
            [['description', 'avatar_url', 'start_time', 'end_time'], 'string'],
            [['owner_id', 'type', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'number'],
            [['name'], 'string', 'max' => 45],
            [['name'], 'trim'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owner::className(), 'targetAttribute' => ['owner_id' => 'owner_id']],
            [['image_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            ['start_time', 'validateTime'],
            ['value', 'validateValue'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function validateTime($attribute, $params, $validator)
    {
        $start_time = new \DateTime($this->start_time);
        $end_time = new \DateTime($this->end_time);
        $now = new \DateTime('NOW');
        if ($start_time < $now)
            $validator->addError($this, $attribute, 'Không thể tạo khuyến mãi trong quá khứ.');

        if ($start_time > $end_time) 
            $validator->addError($this, $attribute, 'Thời gian bắt đầu phải trước thời gian kết thúc.');

        $diff = date_diff($start_time, $end_time);

        $hours = $diff->h;
        $hours = $hours + ($diff->days*24);   
        $hours = (float) $hours + ((float) $diff->i /60);

        if ($hours < 1.0)
            $validator->addError($this, $attribute, 'Thời gian bắt đầu phải cách thời gian kết thúc 1 giờ.');
        
    }

    public function validateValue($attribute, $params, $validator)
    {
        switch ($this->type) {
            case 0:
                $this->value = (int) $this->value;
                if (!is_int($this->value))
                {
                    $validator
                        ->addError($this, $attribute, 'Phải là giá trị phần trăm (0-100) %.');
                    break;
                }
                if ($this->value < 0 || $this->value > 100)
                {
                    $validator
                        ->addError($this, $attribute, 'Giá trị % phải nằm trong khoảng 0-100.');
                    break;
                }  
                break;
            default:
                $validator
                        ->addError($this, 'type', 'Kiểu khuyến mãi không xác định.');
                break;
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'campaign_id' => 'Campaign ID',
            'name' => 'Name',
            'description' => 'Description',
            'owner_id' => 'Owner ID',
            'avatar_url' => 'Avatar Url',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'type' => 'Type',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'image_file' => 'Image File',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(Owner::className(), ['owner_id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaignSubPitches()
    {
        return $this->hasMany(CampaignSubPitch::className(), ['campaign_id' => 'campaign_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubPitches()
    {
        return $this->hasMany(SubPitch::className(), ['sub_pitch_id' => 'sub_pitch_id'])
                    ->viaTable('CampaignSubPitch', ['campaign_id' => 'campaign_id']);
    }

    /** Deletes avatar before delete record.
     *
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            CampaignSubPitch::deleteAll(['campaign_id' => $this->campaign_id]);
        } catch (NotSupportedException $e) {
            Yii::info($e, 'NotSupportedException');
            $transaction->rollBack();
            return false;
        }
        
        $transaction->commit();

        if (isset($this->avatar_url))
        {   
            $filename = Yii::getAlias('@webroot') . '/'. $this->avatar_url;
            if (file_exists($filename))
                return unlink($filename);
        }
        return true;
    }

    public function save($useParent = true, $runValidation = true, $attributeNames = null)
    {   
        if (!$useParent) 
        {
            $this->image_file = UploadedFile::getInstance($this, 'image_file');
            if (isset($this->image_file))
            {
                $path = 'uploads/' . uniqid('CampaignImage', true) . '.' . $this->image_file->extension;
                $old_avatar_url = $this->avatar_url;

                if ($this->upload($path))
                {   
                    Yii::info('check path', 'Path 1');
                    if (isset($old_avatar_url))
                        unlink(Yii::getAlias('@webroot') . '/'. $old_avatar_url);

                    $this->avatar_url = $path;
                }
                else
                    $this->addError('image_file', 'There was an error uploading your image.');
            } 
        }
        
        return parent::save($runValidation, isset($attributeNames) ? $attributeNames : $this->attributes());
    }

    public function upload($path)
    {
        if ($this->validate('image_file')) {
            return $this->image_file->saveAs($path);
        } else {
            return false;
        }
    }
}
