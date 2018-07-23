<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Cập nhật Profile';
?>
<div class="container m-t-80 list-rps">

    <div class="row ">
    	<div class="col-md-6 m-t-30">
            <div class="card">
                <img class="img-fluid" src="<?= $model->avatar_url ? Utils::imgSrc($model->avatar_url) : '/images/gravatar.png' ?>">
            </div>
        </div>
    	<div class="col-md-6">
    		<div class="m-lr-auto width-70">
    			<h2 class="title text-center"><?= Html::encode($this->title) ?></h2>
			    <h2 class="title text-center m-b-20"><?= $model->email ?></h2>
    			<?php $form = ActiveForm::begin(); ?>

			    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'input101'])->label('Số điện thoại') ?>

			    <?= $form->field($model, 'imageFile')->fileInput()->label('Avatar') ?>

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

</div>
