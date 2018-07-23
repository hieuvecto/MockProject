<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use janisto\timepicker\TimePicker;
use borales\extensions\phoneInput\PhoneInput;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-4 p-tb-15 custom-box">
        <?php $form = ActiveForm::begin(); ?>
        <?= $pitchForm->errorSummary($form); ?>

        <?= $form->field($pitchForm->Pitch, 'name')->textInput(['maxlength' => true])->label('Tên sân') ?>

        <?= $form->field($pitchForm->Pitch, 'description')->textarea(['rows' => 4])->label('Mô tả') ?>

        <?= $form->field($pitchForm->Pitch, 'city')->dropdownList([
                        'Đà Nẵng' => 'Đà Nẵng'
                    ])->label('Thành phố') ?>

        <?= $form->field($pitchForm->Pitch, 'district')->dropdownList([
                        'Cẩm Lệ' => 'Cẩm Lệ',
                        'Hải Châu' => 'Hải Châu',
                        'Hòa Vang' => 'Hòa Vang',
                        'Liên Chiểu' => 'Liên Chiểu',
                        'Ngũ Hành Sơn' => 'Ngũ Hành Sơn',
                        'Sơn Trà' => 'Sơn Trà',
                        'Thanh Khê' => 'Thanh Khê',
                    ])->label('Quận/huyện') ?>

        <?= $form->field($pitchForm->Pitch, 'address')->textInput(['maxlength' => true])->label('Địa chỉ') ?>

        <?= $form->field($pitchForm->Pitch, 'phone_number')
            ->widget(PhoneInput::className(), [
            'jsOptions' => [
                'allowExtensions' => true,
                'preferredCountries' => ['vn', 'cn', 'us'],
            ]
        ])->label('Số điện thoại') ?>

        <?= $form->field($pitchForm->Pitch, 'imageFile')->fileInput(['id' => 'fileInput'])->label('Ảnh đại diện') ?>

        <?= $form->field($pitchForm->SubPitch, 'size')->dropdownList([
                        5 => '5 người',
                        7 => '7 người',
                        9 => '9 người',
                        11 => '11 người',
                    ])->label('Loại sân') ?>

        <?= $form->field($pitchForm->SubPitch, 'start_time')
            ->dropdownList(Utils::getTimeArray())->label('Giờ mở cửa') ?>

        <?= $form->field($pitchForm->SubPitch, 'end_time')
            ->dropdownList(Utils::getTimeArray())->label('Giờ đóng cửa') ?>

        <?= $form
            ->field($pitchForm->SubPitch, 'price_per_hour')
            ->textInput(['type' => 'number'])->label('Giá thuê / giờ') ?>

        <?= $form->field($pitchForm->SubPitch, 'currency')
        ->textInput(['maxlength' => true, 'readonly' => true])->label('Mệnh giá') ?>

        <div class="wrap-login101-form-btn">
            <div class="login101-form-bgbtn"></div>
            <?= Html::submitButton('Lưu', ['class' => 'login101-form-btn']) ?>
        </div>

         <?php ActiveForm::end(); ?>  
    </div>   

    <div class="col-md-8 p-tb-15">
        <label class="control-label">Preview ảnh đại diện</label>
        <div class="card"> 
            <?= Utils::catchImg(Utils::imgSrc($pitchForm->Pitch->avatar_url), [
                'class' => 'img-fluid',
                'id' => 'fileInput-preview'
            ]) ?>

        </div>
    </div>
</div>

