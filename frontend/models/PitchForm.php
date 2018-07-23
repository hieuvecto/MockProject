<?php
namespace frontend\models;

use common\models\Pitch;
use common\models\SubPitch;
use Yii;
use yii\base\Model;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

class PitchForm extends Model
{
    public $Pitch;
    public $SubPitch;

    function __construct($pitch = null, $subPitch = null) 
    {
        parent::__construct();
        if (isset($pitch))
            $this->Pitch = $pitch;
        else 
            $this->Pitch = new Pitch();
        

        if (isset($subPitch))
            $this->SubPitch = $subPitch;
        else 
            $this->SubPitch = new SubPitch();
    }

    public function rules()
    {
        return [
            [['Pitch'], 'safe'],
            [['SubPitch'], 'safe'],
        ];
    }

    private function customValidate() 
    {   
        $this->Pitch->owner_id = Yii::$app->owner->identity->owner_id;

        $this->SubPitch->name = $this->Pitch->name;
        $this->SubPitch->description = $this->Pitch->description;
        
        $attributePitch = [
            'pitch_id',
            'name',
            'description',
            'owner_id',
            'city',
            'district',
            'street',
            'apartment_number',
            'phone_number',
            'avatar_url',
            'created_at',
            'updated_at',
        ];

        $attributeSubPitch = [
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
        ];

        $error = false;
        if (!$this->Pitch->validate($attributePitch)) {
            Yii::info($this->Pitch->getErrors(), 'Error validate Pitch');
            $error = true;
        }
        if (!$this->SubPitch->validate($attributeSubPitch)) {
            Yii::info($this->SubPitch->getErrors(), 'Error validate sub Pitch');
            $error = true;
        }
        if ($error) {
            $this->addError('Pitch', 'Error custom validate'); 
            return false;
        }
        return true;
    }

    public function save()
    {   
        if (!$this->customValidate()) {
            Yii::info($this->getErrors(), 'Error save validate');
            return false;
        }
        $transaction = Yii::$app->db->beginTransaction();
        if (!$this->Pitch->save(false)) {
            $transaction->rollBack();
            return false;
        }

        if (isset($this->Pitch->imageFile))
        {   
            $pathSubPitch = 'uploads/' . uniqid('SubPitchImage', true) . '.' . $this->Pitch->imageFile->extension;
            $old_avatar_url_SubPitch = $this->SubPitch->avatar_url;

            $webroot = Yii::getAlias('@webroot') . '/';
            if (copy($webroot . $this->Pitch->avatar_url, 
                     $webroot . $pathSubPitch)              )
            {   
                if (isset($old_avatar_url_SubPitch))
                    unlink(Yii::getAlias('@webroot') . '/'. $old_avatar_url_SubPitch);
                $this->SubPitch->avatar_url = $pathSubPitch;
            }
        }

        $this->SubPitch->pitch_id = $this->Pitch->pitch_id;
        if (!$this->SubPitch->save(false)) {
            $transaction->rollBack();
            return false;
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
            'Pitch' => $this->Pitch,
            'SubPitch' => $this->SubPitch,
        ];
    }
}