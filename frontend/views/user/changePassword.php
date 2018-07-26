<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Đổi mật khẩu';
?>
<div class="container m-b-50">

    <div class="row m-t-30">
    	<div class="col-md-4 m-lr-auto">
    		<h1 class="title text-center m-b-30"><?= Html::encode($this->title) ?></h1>
    		<?php $form = ActiveForm::begin(); ?>

    		<span class="label-input">Mật khẩu cũ</span>
		    <?= $form->field($model, 'old_password')->passwordInput(['class' => 'input101'])->label(false) ?>

		    <span class="label-input">Mật khẩu mới</span>
		    <?= $form->field($model, 'password')->passwordInput(['class' => 'input101'])->label(false) ?>

		    <span class="label-input">Xác nhận mật khẩu</span>
		    <?= $form->field($model, 'password_confirm')->passwordInput(['class' => 'input101'])->label(false) ?>

		    <div class="form-group m-t-30">
                <div class="wrap-login101-form-btn">
                    <div class="login101-form-bgbtn"></div>
                    <button type="submit" class="login101-form-btn">Lưu</button>                            
                </div>
            </div>

		    <?php ActiveForm::end(); ?>	
    	</div>

	</div>

</div>
