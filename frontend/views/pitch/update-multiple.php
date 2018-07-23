<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Cập nhật sân';
?>

<div class="container">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <div class="row">
	    <div class="col-md-4 p-tb-15 custom-box">
	        <?php $form = ActiveForm::begin(); ?>

	        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Tên sân') ?>

	        <?= $form->field($model, 'description')->textarea(['rows' => 4])->label('Mô tả') ?>

	        <?= $form->field($model, 'city')->dropdownList([
	                        'Đà Nẵng' => 'Đà Nẵng'
	                    ])->label('Thành phố') ?>

	        <?= $form->field($model, 'district')->dropdownList([
	                        'Cẩm Lệ' => 'Cẩm Lệ',
	                        'Hải Châu' => 'Hải Châu',
	                        'Hòa Vang' => 'Hòa Vang',
	                        'Liên Chiểu' => 'Liên Chiểu',
	                        'Ngũ Hành Sơn' => 'Ngũ Hành Sơn',
	                        'Sơn Trà' => 'Sơn Trà',
	                        'Thanh Khê' => 'Thanh Khê',
	                    ])->label('Quận/huyện') ?>

	        <?= $form->field($model, 'address')->textInput(['maxlength' => true])->label('Địa chỉ') ?>

	        <?= $form->field($model, 'phone_number')
	            ->widget(PhoneInput::className(), [
	            'jsOptions' => [
	                'allowExtensions' => true,
	                'preferredCountries' => ['vn', 'cn', 'us'],
	            ]
	        ])->label('Số điện thoại') ?>

	        <?= $form->field($model, 'imageFile')->fileInput(['id' => 'fileInput'])->label('Ảnh đại diện') ?>

	        <div class="wrap-login101-form-btn m-t-30">
	            <div class="login101-form-bgbtn"></div>
	            <?= Html::submitButton('Lưu', ['class' => 'login101-form-btn']) ?>
	        </div>

	         <?php ActiveForm::end(); ?>  
	    </div>   

	    <div class="col-md-8 p-tb-15">
	        <label class="control-label">Preview ảnh đại diện</label>
	        <div class="card"> 
	            <?= Utils::catchImg(Utils::imgSrc($model->avatar_url), [
	                'class' => 'img-fluid',
	                'id' => 'fileInput-preview'
	            ]) ?>

	        </div>
	    </div>
	</div>

</div>
