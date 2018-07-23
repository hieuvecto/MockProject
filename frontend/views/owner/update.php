<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Thông tin chủ sân';
?>
<div class="container">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <?= Utils::catchIdentityImg(Utils::imgSrc($model->avatar_url), [
                'class' => 'img-fluid',
                'id' => 'fileInput-preview'
            ]) ?>
            </div>
        </div>
        <div class="col-md-5 custom-box p-tb-15 ">
            <h2 class="title text-center"><?= Html::encode($this->title) ?></h2>
		    <h2 class="title text-center m-b-20"><?= $model->email ?></h2>
			<?php $form = ActiveForm::begin(); ?>

		    <?= $form->field($model, 'phone')->textInput(['maxlength' => true,])->label('Số điện thoại') ?>

		    <?= $form->field($model, 'imageFile')->fileInput(['id' => 'fileInput'])->label('Ảnh đại diện') ?>

		    <div class="form-group m-t-30">
                <div class="wrap-login101-form-btn">
                    <div class="login101-form-bgbtn"></div>
                    <button type="submit" class="login101-form-btn">Lưu</button>                            
                </div>
            </div>
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>

</div>
