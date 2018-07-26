<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="background-fluid">
    
    
    <div class="pitch-background" style="position: relative;">
        <!-- Overlay -->
        <div class="overlay"></div>

        <div class="top-overlay">
            <div class="container-login100">
                <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
                    <?php $form = ActiveForm::begin(['id' => 'signup-form',
                    'options' => [
                            'class' => 'login100-form validate-form'
                    ]]); ?>
                        <span class="login100-form-title p-b-49">
                            Đăng Ký
                        </span>

                        <div class="wrap-input100 validate-input m-b-23" data-validate="Username is required">
                            <span class="label-input100">Email</span>
                            <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'class' => 'input100'])->label(false) ?>
                        </div>

                        <div class="wrap-input100 validate-input m-b-23" data-validate="Phone is required">
                            <span class="label-input100">Số điện thoại</span>
                            <?= $form->field($model, 'phone')->textInput([
                                'type' => 'number',
                                'maxlength' => true, 
                                'class' => 'input100'])->label(false) ?>
                        </div>

                        <div class="wrap-input100 validate-input m-b-23" data-validate="Password is required">
                            <span class="label-input100">Mật khẩu</span>
                            <?= $form->field($model, 'password')->passwordInput([
                            'class' => 'input100'])->label(false) ?>
                        </div>

                        <div class="wrap-input100 validate-input" data-validate="Password is required">
                            <span class="label-input100">Xác nhận mật khẩu</span>
                            <?= $form->field($model, 'password_confirm')->passwordInput([
                            'class' => 'input100'])->label(false) ?>
                        </div>
                        <div class="text-right p-t-8 p-b-31">
                            <a href="#">
                                Quên mật khẩu ?
                            </a>
                        </div>

                        <div class="container-login100-form-btn">
                            <div class="wrap-login100-form-btn">
                                <div class="login100-form-bgbtn"></div>
                                <?= Html::submitButton('Đăng Ký', [
                                    'type' => 'submit',
                                    'class' => 'login100-form-btn', 
                                    'name' => 'login-button']) ?>
                            </div>
                        </div>

                        <div class="txt1 text-center p-t-54 p-b-20">
                            <span>
                                Hoặc Đăng Ký Bằng
                            </span>
                        </div>

                        <div class="flex-c-m">
                            <a href="#" class="login100-social-item bg1">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#" class="login100-social-item bg2">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="<?= Url::to(['user/auth', 'authclient'=> 'google']) ?>" class="login100-social-item bg3">
                                <i class="fa fa-google"></i>
                            </a>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>   
        </div>
        
        
    </div>

</div>
