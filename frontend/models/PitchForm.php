<?php
namespace frontend\models;

use common\models\Pitch;
use common\models\SubPitch;
use Yii;
use yii\base\Model;
use yii\widgets\ActiveForm;

class PitchForm extends Model
{
    public $Pitch;
    public $SubPitch;

    function __construct($pitch, $subPitch) 
    {
        parent::__construct();

        if (!isset($pitch))
            $this->Pitch = new Pitch;
        else 
            $this->Pitch = $pitch;

        if (!isset($subPitch))
            $this->SubPitch = new SubPitch;
        else 
            $this->SubPitch = $subPitch;
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

        $this->SubPitch->name = $this->Pitch->name . ' (1)';
        $this->SubPitch->description = $this->Pitch->description;
        

        Yii::info('Check log after validate');
        $error = false;
        if (!$this->Pitch->validate()) {
            Yii::info($this->Pitch->getErrors(), 'Error validate Pitch');
            $error = true;
        }
        if (!$this->SubPitch->validate()) {
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
        if (!$this->Pitch->save()) {
            $transaction->rollBack();
            return false;
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