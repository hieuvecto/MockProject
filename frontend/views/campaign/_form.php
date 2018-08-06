<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\ActiveField;
use janisto\timepicker\TimePicker;
use borales\extensions\phoneInput\PhoneInput;
use common\helpers\Utils;

$lastIndex = count($campaignForm->sub_pitch_ids) - 1;

$this->registerJs("
$('#campaignform-sub_pitch_ids-plus').click(function(event) {
    event.preventDefault();
    console.log('clicked');
    var field = $('.field-campaignform-sub_pitch_ids');
    if (field.length > 0) {
        var selectInput = $('.field-campaignform-sub_pitch_ids > select')[0];
        var newSelectInput = $(selectInput).clone().addClass('m-t-15');
        newSelectInput.attr('name', 'CampaignForm[sub_pitch_ids][]');
        var helpblock = $('.field-campaignform-sub_pitch_ids > div:last-child');
        newSelectInput.insertBefore(helpblock);
    }
    else {
        var selectInput = $('.field-campaignform-sub_pitch_ids-$lastIndex > select')[0];
        var newSelectInput = $(selectInput).clone().addClass('m-t-15');
        newSelectInput.attr('name', 'CampaignForm[sub_pitch_ids][]');
        var helpblock = $('.field-campaignform-sub_pitch_ids-$lastIndex > div:last-child');
        newSelectInput.insertBefore(helpblock);
    }
});
    ");

$options = [];
foreach ($subPitches as $subPitch) {
    $options[$subPitch->sub_pitch_id] = $subPitch->name;
}

$error = $campaignForm->getErrors('sub_pitch_ids');

/* @var $this yii\web\View */
/* @var $campaignForm->Campaign common\models\Pitch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-4 p-tb-15 custom-box">
        <?php $form = ActiveForm::begin(); ?>

        <?= $campaignForm->errorSummary($form); ?>

        <?= $form->field($campaignForm->Campaign, 'name')->textInput(['maxlength' => true])->label('Tên khuyến mãi') ?>

        <?= $form->field($campaignForm->Campaign, 'description')->textarea(['rows' => 4])->label('Mô tả') ?>

        <?= $form->field($campaignForm->Campaign, 'start_time')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'datetime',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'HH:mm:ss',
                    'showSecond' => true,
                ]
            ])->label('Thời gian bắt đầu') ?>

        <?= $form->field($campaignForm->Campaign, 'end_time')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'datetime',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'HH:mm:ss',
                    'showSecond' => true,
                ]
            ])->label('Thời gian kết thúc') ?>

        <?= $form->field($campaignForm->Campaign, 'image_file')->fileInput(['id' => 'fileInput'])->label('Banner') ?>

        <?= $form
            ->field($campaignForm->Campaign, 'type')
            ->dropdownList([
                0 => 'Giảm giá %',
            ])->label('Kiểu khuyến mãi') ?>

        <?= $form->field($campaignForm->Campaign, 'value')
        ->textInput([ 'type' => 'number'])->label('Giá trị') ?>

        <?php if (isset($campaignForm->sub_pitch_ids)): ?>
            <div class="form-group <?= count($error) > 0 ? 'has-error' : '' ?>">
                <label class="control-label">
                    Áp dụng cho sân
                </label>
                <?php foreach ($campaignForm->sub_pitch_ids as $key => $value): ?>
                    <?php if ($key < count($campaignForm->sub_pitch_ids) - 1): ?>
                        <select class="form-control <?= $key > 0 ? 'm-t-15' : '' ?>" 
                            name="CampaignForm[sub_pitch_ids][<?= $key ?>]">  
                            <?php foreach ($subPitches as $subPitch): ?>
                                <option value="<?= $subPitch->sub_pitch_id ?>" 
                                <?= $subPitch->sub_pitch_id == $value ? 'selected' : '' ?>  >
                                    <?= $subPitch->name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                <?php endforeach; ?>
                <div class="help-block"></div>
            </div>

            <?= $form->field($campaignForm, 'sub_pitch_ids['. (count($campaignForm->sub_pitch_ids) - 1). ']')
            ->dropdownList($options)->label(false) ?>

        <?php else: ?>
            <?= $form->field($campaignForm, 'sub_pitch_ids[]')
            ->dropdownList($options)->label('Áp dụng cho sân') ?>
        <?php endif; ?>
        <button id="campaignform-sub_pitch_ids-plus" class="float-right m-b-20"><i class="fa fa-plus" aria-hidden="true"></i></button>

        <div class="wrap-login101-form-btn">
            <div class="login101-form-bgbtn"></div>
            <?= Html::submitButton('Lưu', ['class' => 'login101-form-btn']) ?>
        </div>

         <?php ActiveForm::end(); ?>  
    </div>   

    <div class="col-md-8 p-tb-15">
        <label class="control-label">Preview banner</label>
        <div class="card"> 
            <?= Utils::catchImg(Utils::imgSrc($campaignForm->Campaign->avatar_url), [
                'class' => 'img-fluid',
                'id' => 'fileInput-preview'
            ]) ?>

        </div>
    </div>
</div>

