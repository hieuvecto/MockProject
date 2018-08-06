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

    <section class="content-header">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-futbol-o"></i> Quản lý sân</a></li>
        <li><?= Html::a('Danh sách', ['pitch/index', 'sort' => '-created_at']) ?></a></li>
        <li class="active">Chi tiết sân</li>
      </ol>
    </section>

    <div class="row">
        <div class="col-md-5 custom-box p-tb-15">
            <div class="row">
                <div class="col-xs-12 m-b-15 icon-group">

                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $pitch->pitch_id], [
                                    'title' => 'Cập nhật',
                                    'aria-label' => 'Cập nhật',
                                ]) ?>

                    <?= Html::a('<i class="fa fa-expand"></i>', 
                                [
                                    'extend', 
                                    'id' => $pitch->pitch_id
                                ],
                                [
                                    'title' => 'Mở rộng sân',
                                    'aria-label' => 'Mở rộng sân',
                                ] 
                                ) ?>

                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete',
                        'id' => $pitch->pitch_id], [
                        'class' => 'color-danger',
                        'title' => 'Xóa',
                        'aria-label' => 'Xóa',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
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
            <div class="row">
                <div class="col-xs-12 icon-group">
                     <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['sub-pitch/update', 'id' => $model->sub_pitch_id], [
                                    'title' => 'Cập nhật',
                                    'aria-label' => 'Cập nhật',
                                ]) ?>
                
                    <?= Html::a('<i class="fa fa-bar-chart"></i>', 
                                [
                                    'sub-pitch/statistic', 
                                    'id' => $model->sub_pitch_id
                                ],
                                [
                                    'title' => 'Thống kê',
                                    'aria-label' => 'Thống kê',
                                ]
                                ) ?>
                    <?= Html::a('<i class="fa fa-plus"></i>', 
                                [
                                    'sub-pitch/create-booking', 
                                    'id' => $model->sub_pitch_id
                                ],
                                [
                                    'title' => 'Đặt sân tại chỗ',
                                    'aria-label' => 'Đặt sân tại chỗ',
                                ] 
                                ) ?>

                    <a href="#" class="link-modal" data-id="<?= $model->sub_pitch_id ?>"
                        title="Giờ trống sân" aria-label="Giờ trống sân">
                        <i class="fa fa-clock-o"></i>
                    </a>

                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['sub-pitch/delete',
                        'id' => $model->sub_pitch_id], [
                        'class' => 'color-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>
            <div>
                <div class="float-right">
                    <?php $count = $model->getBookings(['is_verified' => 0])->count(); ?>
                    <?= $count > 0 ? 'Sân có ' . $count . ' đặt sân chưa thanh toán.':  'Sân chưa có thêm đặt sân nào.'?>   
                    <?= Html::a('Danh sách', ['sub-pitch/list-booking', 'id' => $model->sub_pitch_id, 
                    'sort' => '-created_at'], ['class' => 'btn btn-hero btn-sm ']) ?>
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
