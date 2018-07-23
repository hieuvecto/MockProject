<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "User".
 *
 * @property int $user_id
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $avatar_url
 * @property int $user_status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Booking[] $bookings
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{   
    public $old_password;
    public $password_confirm;
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'User';
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
            [['email', 'password', 'phone'], 'required'],
            [['avatar_url'], 'string'],
            [['user_status', 'created_at', 'updated_at'], 'integer'],
            [['email'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 13],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['email', 'phone'], 'trim'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['old_password', 'password_confirm'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'avatar_url' => 'Avatar Url',
            'user_status' => 'User Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'image_file' => 'Image File',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['user_id' => 'user_id']);
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

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);  
    }   

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->user_id;  
    }

    /**
     * @return string current admin auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current admin
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    public function save($useParent = true, $runValidation = true, $attributeNames = null)
    {   
        if (!$useParent) 
        {
            $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
            if (isset($this->imageFile))
            {
                $path = 'uploads/' . uniqid('UserImage', true) . '.' . $this->imageFile->extension;
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
            'user_id',
            'email',
            'password',
            'phone',
            'avatar_url',
            'user_status',
            'created_at',
            'updated_at',
            'auth_key',
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

    static public function findByEmail($email)
    {
        return User::find()
            ->where(['email' => $email])
            ->one();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
