<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "CampaignSubPitch".
 *
 * @property int $campaign_sub_pitch_id
 * @property int $campaign_id
 * @property int $sub_pitch_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Campaign $campaign
 * @property SubPitch $subPitch
 */
class CampaignSubPitch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CampaignSubPitch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'sub_pitch_id'], 'required'],
            [['campaign_id', 'sub_pitch_id', 'created_at', 'updated_at'], 'integer'],
            [['campaign_id'], 'exist', 'skipOnError' => true, 'targetClass' => Campaign::className(), 'targetAttribute' => ['campaign_id' => 'campaign_id']],
            [['sub_pitch_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubPitch::className(), 'targetAttribute' => ['sub_pitch_id' => 'sub_pitch_id']],
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
            'campaign_sub_pitch_id' => 'Campaign Sub Pitch ID',
            'campaign_id' => 'Campaign ID',
            'sub_pitch_id' => 'Sub Pitch ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCampaign()
    {
        return $this->hasOne(Campaign::className(), ['campaign_id' => 'campaign_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubPitch()
    {
        return $this->hasOne(SubPitch::className(), ['sub_pitch_id' => 'sub_pitch_id']);
    }
}
