<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use janisto\timepicker\TimePicker;
use borales\extensions\phoneInput\PhoneInput;
use common\helpers\Utils;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Mở rộng sân';
$model->currency = 'VND';

?>
<div class="container">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>

   	<div class="row">
   		<div class="col-md-4 p-tb-15 custom-box">
   		<?php $form = ActiveForm::begin(); ?>

		    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Tên sân') ?>

	        <?= $form->field($model, 'description')->textarea(['rows' => 4])->label('Mô tả') ?>

	        <?= $form->field($model, 'imageFile')->fileInput(['id' => 'fileInput'])->label('Ảnh đại diện') ?>

	        <?= $form->field($model, 'size')->dropdownList([
                        5 => '5 người',
                        7 => '7 người',
                        9 => '9 người',
                        11 => '11 người',
                    ])->label('Loại sân') ?>

	        <?= $form->field($model, 'start_time')
	            ->dropdownList(Utils::getTimeArray())->label('Giờ mở cửa') ?>

	        <?= $form->field($model, 'end_time')
	            ->dropdownList(Utils::getTimeArray())->label('Giờ đóng cửa') ?>

	        <?= $form
	            ->field($model, 'price_per_hour')
	            ->textInput(['type' => 'number'])->label('Giá thuê / giờ') ?>

	        <?= $form->field($model, 'currency')
	        ->textInput(['maxlength' => true, 'readonly' => true])->label('Mệnh giá') ?>

	        <div class="wrap-login101-form-btn">
	            <div class="login101-form-bgbtn"></div>
	            <?= Html::submitButton('Mở rộng', ['class' => 'login101-form-btn']) ?>
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
