<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use borales\extensions\phoneInput\PhoneInputValidator;

/**
 * This is the model class for table "Pitch".
 *
 * @property int $pitch_id
 * @property string $name
 * @property string $description
 * @property int $owner_id
 * @property string $city
 * @property string $district
 * @property string $street
 * @property int $apartment_number
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
            [['name', 'owner_id', 'city', 'district', 'street', 'apartment_number', 'phone_number'], 'required'],
            [['description'], 'string'],
            [['owner_id', 'apartment_number', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['city', 'district'], 'string', 'max' => 20],
            [['street'], 'string', 'max' => 30],
            [['phone_number'], 'string', 'max' => 13],
            [['name'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owner::className(), 'targetAttribute' => ['owner_id' => 'owner_id']],
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
            'street' => 'Street',
            'apartment_number' => 'Apartment Number',
            'phone_number' => 'Phone Number',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
}
