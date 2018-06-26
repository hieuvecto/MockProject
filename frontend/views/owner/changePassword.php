<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Owner */

$this->title = 'Change password: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Owners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->owner_id, 'url' => ['view', 'id' => $model->owner_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="owner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="owner-form">

	    <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'old_password')->passwordInput() ?>

	    <?= $form->field($model, 'password')->passwordInput() ?>

	    <?= $form->field($model, 'password_confirm')->passwordInput() ?>

	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
