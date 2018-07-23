<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\web\UploadedFile;

/**
 * This is the model class for table "Pitch".
 *
 * @property int $pitch_id
 * @property string $name
 * @property string $description
 * @property int $owner_id
 * @property string $city
 * @property string $district
 * @property string $address
 * @property string $phone_number
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Owner $owner
 * @property SubPitch[] $subPitches
 */
class Pitch extends \yii\db\ActiveRecord
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
        return 'Pitch';
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
            [['name', 'owner_id', 'city', 'district', 'address', 'phone_number'], 'required'],
            [['description', 'avatar_url'], 'string'],
            [['owner_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['city', 'district'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 40],
            [['phone_number'], 'string', 'max' => 13],
            [['name'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owner::className(), 'targetAttribute' => ['owner_id' => 'owner_id']],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pitch_id' => 'Pitch ID',
            'name' => 'Name',
            'description' => 'Description',
            'owner_id' => 'Owner ID',
            'city' => 'City',
            'district' => 'District',
            'address' => 'Address',
            'phone_number' => 'Phone Number',
            'avatar_url' => 'Avatar Url',
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
    public function getSubPitches()
    {
        return $this->hasMany(SubPitch::className(), ['pitch_id' => 'pitch_id']);
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
                $path = 'uploads/' . uniqid('PitchImage', true) . '.' . $this->imageFile->extension;
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
        
        return parent::save($runValidation, [ 
            'pitch_id',
            'name',
            'description',
            'owner_id',
            'city',
            'district',
            'address',
            'phone_number',
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
