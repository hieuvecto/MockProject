<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use janisto\timepicker\TimePicker;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model common\models\SubPitch */

$this->title = 'Update Sub Pitch: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sub Pitches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->sub_pitch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-pitch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="sub-pitch-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	    <?= $form->field($model, 'start_time')
		    	->widget(TimePicker::className(), [
				    //'language' => 'fi',
				    'mode' => 'time',
				    'clientOptions' => [
				        'hour' => date('H'),
				        'minute' => date('i'),
				        'second' => date('s'),
			    	]
				]) ?>

	    <?= $form->field($model, 'end_time')
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
	    	->field($model, 'price_per_hour')
	    	->textInput(['type' => 'number']) ?>

	    <?= $form->field($model, 'currency')
	    	->textInput(['maxlength' => true, 'readonly' => true]) ?>

	    <?= $form->field($model, 'imageFile')->fileInput() ?>
	    
	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
