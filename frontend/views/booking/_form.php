<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use janisto\timepicker\TimePicker;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\Booking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-4">
        <h1 class="title text-center"><?= $this->title ?></h1>
        <?php $form = ActiveForm::begin([
            'options' => [
                'class' => 'm-t-20',
            ]
        ]); ?>

            <?= $form->field($model, 'book_day')
                    ->widget(TimePicker::className(), [
                        //'language' => 'fi',
                        'mode' => 'date',
                        'clientOptions' => [
                            'dateFormat' => 'yy-mm-dd',
                        ]
                    ])->label('Ngày đặt sân') ?>

            <?= $form->field($model, 'start_time')
                    ->dropdownList(Utils::getTimeArray())->label('Đặt từ') ?>

            <?= $form->field($model, 'book_range')
                    ->dropdownList([
                        10 => '1 giờ',
                        15 => '1.5 giờ',
                        20 => '2 giờ',
                        25 => '2.5 giờ',
                        30 => '3 giờ',
                    ])->label('Đặt bao lâu') ?>

            <?= $form->field($model, 'message')->textarea(['rows' => 6])->label('Tin nhắn cho chủ sân') ?>

            <div class="form-group">
                <div class="wrap-login101-form-btn">
                    <div class="login101-form-bgbtn"></div>
                    <button type="submit" class="login101-form-btn">Đặt sân</button>                            
                </div>
            </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-8">
        <?= $this->render('_sub_pitch_detail.twig', [
            'subPitch' => $subPitch,
            'pitch' => $pitch,
            'class' => isset($class) ? $class : '',
        ]) ?>
    </div>
</div>
