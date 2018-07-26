<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "AuthOwner".
 *
 * @property int $auth_id
 * @property int $owner_id
 * @property string $source
 * @property string $source_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Owner $owner
 */
class AuthOwner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'AuthOwner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'source', 'source_id'], 'required'],
            [['owner_id', 'created_at', 'updated_at'], 'integer'],
            [['source', 'source_id'], 'string', 'max' => 255],
            [['owner_id'], 'unique'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Owner::className(), 'targetAttribute' => ['owner_id' => 'owner_id']],
        ];
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
    public function attributeLabels()
    {
        return [
            'auth_id' => 'Auth ID',
            'owner_id' => 'Owner ID',
            'source' => 'Source',
            'source_id' => 'Source ID',
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
}
