<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use janisto\timepicker\TimePicker;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pitch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $pitchForm->errorSummary($form); ?>

    <fieldset>
        <legend>Pitch</legend>
        <?= $form->field($pitchForm->Pitch, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($pitchForm->Pitch, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($pitchForm->Pitch, 'city')->textInput(['maxlength' => true]) ?>

        <?= $form->field($pitchForm->Pitch, 'district')->textInput(['maxlength' => true]) ?>

        <?= $form->field($pitchForm->Pitch, 'street')->textInput(['maxlength' => true]) ?>

        <?= $form->field($pitchForm->Pitch, 'apartment_number')->textInput() ?>

        <?= $form->field($pitchForm->Pitch, 'phone_number')
            ->widget(PhoneInput::className(), [
            'jsOptions' => [
                'allowExtensions' => true,
                'preferredCountries' => ['vn', 'cn', 'us'],
            ]
        ])?>

        <?= $form->field($pitchForm->Pitch, 'imageFile')->fileInput() ?>
    </fieldset>

    <fieldset>
        <legend>SubPitch</legend>
        <?= $form->field($pitchForm->SubPitch, 'start_time')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'time',
                'clientOptions' => [
                    'hour' => date('H'),
                    'minute' => date('i'),
                    'second' => date('s'),
                ]
            ]) ?>

        <?= $form->field($pitchForm->SubPitch, 'end_time')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'time',
                'clientOptions' => [
                    'hour' => date('H'),
                    'minute' => date('i'),
                    'second' => date('s'),
                ]
            ]) ?>

        <?= $form
            ->field($pitchForm->SubPitch, 'price_per_hour')
            ->textInput(['type' => 'number']) ?>

        <?= $form->field($pitchForm->SubPitch, 'currency')
        ->textInput(['maxlength' => true, 'readonly' => true]) ?>
             
    </fieldset>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
