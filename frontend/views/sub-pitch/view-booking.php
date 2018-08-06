<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\Booking */

$this->title = 'Chi tiết đặt sân';

?>
<div class="container">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-5 custom-box p-tb-15">
            <div>
                <?= !$model->is_verified ? Html::a('Xác nhận', ['verify', 'booking_id' => $model->booking_id], [
                    'class' => 'btn btn-hero btn-md',
                    'data' => [
                        'confirm' => 'Bạn có chắc muốn xác nhận đặt sân này?',
                        'method' => 'post',
                    ],
                ]) : '' ?>

                <?= $model->is_verified && !$model->is_paid ? Html::a('Thanh toán', ['pay', 'booking_id' => $model->booking_id], [
                    'class' => 'btn btn-hero btn-md',
                    'data' => [
                        'confirm' => 'Bạn có muốn xác nhận thanh toán đặt sân này?',
                        'method' => 'post',
                    ],
                ]) : '' ?>
            </div>

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Người đặt sân',
                        'value' => $user->email,
                    ],
                    [
                        'label' => 'Số điện thoại',
                        'value' => $user->phone,
                    ],
                    [
                        'label' => 'Ngày đặt sân',
                        'attribute' => 'book_day',
                    ],
                    [
                        'label' => 'Bắt đầu lúc',
                        'attribute' => 'start_time',
                        'format' => ['time', 'php:H:i'],
                    ],
                    [
                        'label' => 'Kết thúc lúc',
                        'attribute' => 'end_time',
                        'format' => ['time', 'php:H:i'],
                    ],
                     [
                        'label' => 'Tin nhắn',
                        'attribute' => 'message',
                        'format' => ['text'],
                    ],
                    [   
                        'label' => 'Xác nhận?',
                        'attribute' => 'is_verified',
                        'format' => 'raw',
                        'value' => function($data) 
                        {
                            return $data->is_verified ? 
                            '<i class="fa fa-check color-success" aria-hidden="true"></i>' : 
                            '<i class="fa fa-times color-danger" aria-hidden="true"></i>';
                        },
                    ],
                    [   
                        'label' => 'Thanh toán?',
                        'attribute' => 'is_paid',
                        'format' => 'raw',
                        'value' => function($data) 
                        {
                            return $data->is_paid ? 
                            '<i class="fa fa-check color-success" aria-hidden="true"></i>' : 
                            '<i class="fa fa-times color-danger" aria-hidden="true"></i>';
                        },
                    ],
                    [
                        'label' => 'Thành tiền',
                        'attribute' => 'total_price',
                        'value' => function($data)
                        {
                            return number_format($data->total_price, 0, '.', ',') . ' VND';
                        }
                    ],
                    [
                        'label' => 'Tạo lúc',
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    ],
                    [
                        'label' => 'Lần cuối cập nhật',
                        'attribute' => 'updated_at',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                        'filter' => '',
                    ],
                ],
            ]) ?>

            <div class="card for-mobile">
                <?= Utils::catchIdentityImg(Utils::imgSrc($user->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>

        <div class="col-md-5 not-mobile">
            <div class="card">
                <?= Utils::catchIdentityImg(Utils::imgSrc($user->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
    </div>
    

    <h2 class="title">Thông tin sân bóng</h2>
    <div class="row">
        <div class="col-md-5 custom-box p-tb-15">
            <?= DetailView::widget([
                'model' => $pitch,
                'attributes' => [
                    [
                        'label' => 'Tên sân',
                        'attribute' => 'name',
                        'format' => 'raw',
                        'value' => function($data)
                        {   
                            return Html::a($data->name, 
                                    ['pitch/view','id'=> $data->pitch_id], 
                                    ['title' => 'View','class'=>'no-pjax']);
                        },
                    ],
                    [   
                        'label' => 'Mô tả',
                        'attribute' => 'description',
                        'format' => 'text',
                    ],
                    [
                        'label' => 'Thành phố/Tỉnh',
                        'attribute' => 'city',
                    ],
                    [
                        'label' => 'Quận/Huyện',
                        'attribute' => 'district',
                    ],
                    [
                        'label' => 'Địa chỉ',
                        'attribute' => 'address',
                    ],
                    [
                        'label' => 'Số điện thoại',
                        'attribute' => 'phone_number',
                    ],
                    [   
                        'label' => 'Loại sân',
                        'value' => $subPitch->size . ' người'
                    ],
                    [
                        'label' => 'Giờ mở cửa',
                        'format' => ['time', 'php:H:i'],
                        'value' => $subPitch->start_time,
                    ],
                    [
                        'label' => 'Giờ đóng cửa',
                        'attribute' => 'end_time',
                        'format' => ['time', 'php:H:i'],
                        'value' => $subPitch->end_time,
                    ],
                    [   
                        'label' => 'Giá thuê / giờ',
                        'attribute' => 'Price Per Hour',
                        'value' => number_format($subPitch->price_per_hour, 0, '.', ',') . ' ' . $subPitch->currency
                    ],
                    [
                        'label' => 'Tạo lúc',
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    ],
                    [
                        'label' => 'Lần cuối cập nhật',
                        'attribute' => 'updated_at',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    ],
                ],
            ]) ?>

            <div class="card for-mobile">
                <?= Utils::catchImg(Utils::imgSrc($pitch->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
        <div class="col-md-5 not-mobile">
            <div class="card">
                <?= Utils::catchImg(Utils::imgSrc($pitch->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
    </div>

</div>
