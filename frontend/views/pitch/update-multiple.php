<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Update Pitch: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pitches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->pitch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="pitch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="pitch-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

	    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'district')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

	    <?= $form->field($model, 'apartment_number')->textInput() ?>

	    <?= $form->field($model, 'phone_number')
            ->widget(PhoneInput::className(), [
            'jsOptions' => [
                'allowExtensions' => true,
                'preferredCountries' => ['vn', 'cn', 'us'],
            ]
        ])?>

	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
