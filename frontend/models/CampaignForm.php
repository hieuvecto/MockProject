<?php
namespace frontend\models;

use common\models\Campaign;
use common\models\CampaignSubPitch;
use common\models\SubPitch;
use common\helpers\Utils;
use Yii;
use yii\base\Model;
use yii\base\InvalidCallException;
use yii\base\NotSupportedException;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

class CampaignForm extends Model
{
    public $Campaign;
    public $sub_pitch_ids;

    function __construct(Campaign $campaign = null, array $sub_pitch_ids = null) 
    {
        parent::__construct();
        if (isset($campaign))
            $this->Campaign = $campaign;
        else
            $this->Campaign = new Campaign();

        if (isset($sub_pitch_ids))
            $this->sub_pitch_ids = $sub_pitch_ids;
    }

    public function rules()
    {
        return [
            [['Pitch'], 'safe'],
            [['sub_pitch_ids'], 'each', 'rule' => ['integer']],
            ['sub_pitch_ids', 'validateIds']
        ];
    }

    public function validateIds($attribute, $params, $validator)
    {
        $this->$attribute = array_unique($this->$attribute);
    }

    public function save()
    {   
        $this->Campaign->owner_id = Yii::$app->owner->identity->owner_id;

        if (!$this->validate()) {
            Yii::info($this->getErrors(), 'Error save validate');
            return false;
        }

        $subPitches = [];

        foreach ($this->sub_pitch_ids as $key => $sub_pitch_id) {
            $subPitches[] = SubPitch::findOne($sub_pitch_id);
            if ($subPitches[$key] === null) {
                $this->addError('Campaign', 'SubPitch not found.');
                return false;
            }
        }

        $transaction = Yii::$app->db->beginTransaction();

        if (!$this->Campaign->save(false)) {
            $transaction->rollBack();
            return false;
        }

        $originSubPitches = $this->Campaign->getSubPitches()->all();

        $origin_sub_pitch_ids = [];

        foreach ($originSubPitches as $subPitch) {
            $origin_sub_pitch_ids[] = $subPitch->sub_pitch_id;
        }

        $diff = array_diff($origin_sub_pitch_ids, $this->sub_pitch_ids);

        foreach ($diff as $sub_pitch_id) {
            try {
                CampaignSubPitch::deleteAll([
                    'campaign_id' => $this->Campaign->campaign_id,
                    'sub_pitch_id' => $sub_pitch_id,
                ]);
            } catch (NotSupportedException $e) {
                Yii::info($e, 'NotSupportedException');
                $transaction->rollBack();
                return false;
            }
            
        }

        foreach ($subPitches as $subPitch) {
            try {
                if (!in_array($subPitch->sub_pitch_id, $origin_sub_pitch_ids))
                    $subPitch->link('campaigns', $this->Campaign);
            } catch (InvalidCallException $e) {
                Yii::info($e, 'InvalidCallException');
                $transaction->rollBack();
                return false;
            }
            
        }

        $transaction->commit();
        return true;
    }

    public function errorSummary($form)
    {
        $errorLists = [];
        foreach ($this->getAllModels() as $id => $model) {
            $errorList = $form->errorSummary($model, [
                'header' => '<p>Please fix the following errors for <b>' . $id . '</b></p>',
            ]);
            $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error
            $errorLists[] = $errorList;
        }
        return implode('', $errorLists);
    }

    private function getAllModels()
    {
        return [

        ];
    }
}