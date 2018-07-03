<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use janisto\timepicker\TimePicker;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Extend pitch';
$this->params['breadcrumbs'][] = ['label' => 'Pitches', 'url' => ['extend']];
$this->params['breadcrumbs'][] = $this->title;
$model->currency = 'VND';

?>
<div class="pitch-create">

    <h1><?= Html::encode($this->title) ?></h1>

   	<div class="pitch-form">

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

	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
