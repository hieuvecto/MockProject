<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
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
            [['email', 'password', 'phone', 'created_at', 'updated_at'], 'required'],
            [['avatar_url'], 'string'],
            [['user_status', 'created_at', 'updated_at'], 'integer'],
            [['email'], 'string', 'max' => 40],
            [['password'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 13],
            [['email'], 'unique'],
            [['phone'], 'unique'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['user_id' => 'user_id']);
    }


    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
       
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
}
