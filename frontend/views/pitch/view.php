<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $pitch common\models\Pitch */

$this->title = $pitch->name;
?>
<div class="container">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-5 custom-box p-tb-15">
            <div class="row">
                <div class="col-xs-5">
                    <?= Html::a('Cập nhật', ['update', 'id' => $pitch->pitch_id], ['class' => 'btn btn-hero btn-md btn-fluid']) ?>
                </div>
                <div class="col-xs-5">
                    <?= Html::a('Mở rộng sân', ['extend', 'id' => $pitch->pitch_id], ['class' => 'btn btn-hero btn-md btn-fluid']) ?>
                </div>
            </div>
            <div>
                <div class="float-right">
                    Sân có <?= $subPitch->getBookings(['is_verified' => 0])->count() ?> đặt sân chưa xác nhận. 
                    <?= Html::a('Xác nhận', ['sub-pitch/list-booking', 'id' => $subPitch->sub_pitch_id, 
                    'BookingSearch' => [ 'is_verified' => 0]], ['class' => 'btn btn-hero btn-sm ']) ?>
                </div>
            </div>
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
                                    ['view','id'=> $data->pitch_id], 
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
                        'label' => 'Chưa xác nhận đặt sân?',
                        'value' => function($data) {
                            $subPitches = $data->getSubPitches()->all();
                            $count = 0;

                            foreach ($subPitches as $subPitch) 
                            {   
                                // count unverified bookings
                                $count += $subPitch->getBookings(['is_verified' => 0])->count();
                            }
                            return $count;
                        },
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
            <div class="row">
                <div class="col-xs-4">
                    <Button type="button" class="btn-modal btn btn-hero btn-md btn-fluid" data-id="<?= $subPitch->sub_pitch_id ?>">Giờ trống sân</Button>
                </div>
                <div class="col-xs-4">
                    <?= Html::a('Thống kê', 
                                [
                                    'sub-pitch/statistic', 
                                    'id' => $subPitch->sub_pitch_id
                                ], 
                                ['class' => 'btn btn-hero btn-md btn-fluid']) ?>
                </div>
                 <div class="col-xs-4">
                    <?= Html::a('Xóa', ['delete', 'id' => $pitch->pitch_id], [
                        'class' => 'btn btn-danger btn-md btn-fluid',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <?= Utils::catchImg(Utils::imgSrc($pitch->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
    </div>

</div>
