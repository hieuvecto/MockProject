<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = $pitch->name;
$this->params['breadcrumbs'][] = ['label' => 'Pitches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container list-rps">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-5 custom-box p-tb-15">
            <div class="row not-mobile">
                <div class="col-xs-4">
                    <?= Html::a('Cập nhật', ['update', 'id' => $pitch->pitch_id], ['class' => 'btn btn-hero btn-md btn-fluid']) ?>
                </div>
                <div class="col-xs-4">
                    <?= Html::a('Mở rộng sân', ['extend', 'id' => $pitch->pitch_id], ['class' => 'btn btn-hero btn-md btn-fluid']) ?>
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

            <div class="for-mobile">
                <?= Html::a('Cập nhật', ['update', 'id' => $pitch->pitch_id], ['class' => 'btn btn-hero btn-md']) ?>
                <?= Html::a('Mở rộng sân', ['extend', 'id' => $pitch->pitch_id], ['class' => 'btn btn-hero btn-md']) ?>
                <?= Html::a('Xóa', ['delete', 'id' => $pitch->pitch_id], [
                        'class' => 'btn btn-danger btn-md',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
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

        <div class="col-md-7 not-mobile">
            <div class="card">
                <?= Utils::catchImg(Utils::imgSrc($pitch->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
    </div>

    <h2 class="title m-t-30">Sân hiện có <?= count($subPitches) ?> sân con</h2>

    <?php foreach ($subPitches as $model): ?>
    <div class="row m-b-20">
        <div class="col-md-5 not-mobile">
            <div class="card">
                <?= Utils::catchImg(Utils::imgSrc($model->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
        <div class="col-md-5 custom-box p-tb-15">
            <div class="row not-mobile">
                <div class="col-xs-4">
                    <?= Html::a('Cập nhật', ['sub-pitch/update', 'id' => $model->sub_pitch_id], ['class' => 'btn btn-hero btn-md btn-fluid']) ?>
                </div>
                <div class="col-xs-4"">
                    <Button type="button" class="btn-modal btn btn-hero btn-md btn-fluid" data-id="<?= $model->sub_pitch_id ?>">Giờ trống sân</Button>
                </div>
                <div class="col-xs-4">
                    <?= Html::a('Xóa', ['sub-pitch/delete', 'id' => $model->sub_pitch_id], [
                        'class' => 'btn btn-danger btn-md btn-fluid',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="for-mobile">
                <?= Html::a('Cập nhật', ['sub-pitch/update', 'id' => $model->sub_pitch_id], ['class' => 'btn btn-hero btn-md']) ?>
                <Button type="button" class="btn-modal btn btn-hero btn-md" data-id="<?= $model->sub_pitch_id ?>">Giờ trống sân</Button>
                <?= Html::a('Xóa', ['sub-pitch/delete', 'id' => $model->sub_pitch_id], [
                        'class' => 'btn btn-danger btn-md',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
            </div>
            <div>
                <div class="float-right">
                    Sân có <?= $model->getBookings(['is_verified' => 0])->count() ?> đặt sân chưa xác nhận. 
                    <?= Html::a('Xác nhận', ['sub-pitch/list-booking', 'id' => $model->sub_pitch_id], ['class' => 'btn btn-hero btn-md ']) ?>
                </div>
            </div>
            <?= DetailView::widget([
                'model' => $model,
                'id' => 'sub-pitch-' . $model->sub_pitch_id,
                'attributes' => [
                    [
                        'label' => 'Tên sân con',
                        'attribute' => 'name',
                    ],
                    [   
                        'label' => 'Mô tả',
                        'attribute' => 'description',
                        'format' => 'text',
                    ],
                    [   
                        'label' => 'Loại sân',
                        'value' => $model->size . ' người'
                    ],
                    [
                        'label' => 'Giờ mở cửa',
                        'attribute' => 'start_time',
                        'format' => ['time', 'php:H:i'],

                    ],
                    [
                        'label' => 'Giờ đóng cửa',
                        'attribute' => 'end_time',
                        'format' => ['time', 'php:H:i'],
                    ],
                    [   
                        'label' => 'Giá thuê / giờ',
                        'attribute' => 'Price Per Hour',
                        'value' => number_format($model->price_per_hour, 0, '.', ',') . ' ' . $model->currency
                    ],
                    [
                        'label' => 'Chưa xác nhận đặt sân',
                        'value' => $model->getBookings(['is_verified' => 1])->count(),
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
                <?= Utils::catchImg(Utils::imgSrc($model->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
    </div>  
    <?php endforeach ?>    
    
</div>
