<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "Owner".
 *
 * @property int $owner_id
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $avatar_url
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Pitch[] $pitches
 */
class Owner extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{   
    public $old_password;
    public $password_confirm;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Owner';
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
            [['created_at', 'updated_at'], 'integer'],
            [['email'], 'string', 'max' => 40],
            [['password', 'old_password', 'password_confirm'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 13],
            [['email'], 'unique'],
            [['phone'], 'unique'],
            [['email', 'phone'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'owner_id' => 'Owner ID',
            'email' => 'Email',
            'password' => 'Password',
            'old_password' => 'Old Password',
            'password_confirm' => 'Password Confirm',
            'phone' => 'Phone',
            'avatar_url' => 'Avatar Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPitches()
    {
        return $this->hasMany(Pitch::className(), ['owner_id' => 'owner_id']);
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
        return $this->owner_id;
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

    static public function findByEmail($email)
    {
        return Owner::find()
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
