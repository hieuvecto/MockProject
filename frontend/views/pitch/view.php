<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $pitch common\models\Pitch */

$this->title = $pitch->name;
?>
<div class="container">
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
                <div class="col-xs-12 icon-group">
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $pitch->pitch_id], [
                        'title' => 'Cập nhật',
                        'aria-label' => 'Cập nhật',
                    ]) ?>
                
                    <?= Html::a('<i class="fa fa-bar-chart"></i>', 
                                [
                                    'sub-pitch/statistic', 
                                    'id' => $subPitch->sub_pitch_id
                                ],
                                [
                                    'title' => 'Thống kê',
                                    'aria-label' => 'Thống kê',
                                ] 
                                ) ?>
                    <?= Html::a(' <i class="fa fa-expand"></i>', 
                                [
                                    'extend', 
                                    'id' => $pitch->pitch_id
                                ],
                                [
                                    'title' => 'Mở rộng sân',
                                    'aria-label' => 'Mở rộng sân',
                                ]  
                                ) ?>
                    <?= Html::a(' <i class="fa fa-plus"></i>', 
                                [
                                    'sub-pitch/create-booking', 
                                    'id' => $subPitch->sub_pitch_id
                                ],
                                [
                                    'title' => 'Đặt sân tại chỗ',
                                    'aria-label' => 'Đặt sân tại chỗ',
                                ]  
                                ) ?>

                    <a href="#" class="link-modal" data-id="<?= $subPitch->sub_pitch_id ?>"
                        title="Giờ trống sân" aria-label="Giờ trống sân">
                        <i class="fa fa-clock-o"></i>
                    </a>

                    <?= Html::a(' <span class="glyphicon glyphicon-trash"></span>', ['delete',
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
            <div>
                <div class="float-right">
                    <?php $count = $subPitch->getBookings(['is_verified' => 0])->count(); ?>
                    <?= $count > 0 ? 'Sân có ' . $count . ' đặt sân chưa thanh toán.':  'Sân chưa có thêm đặt sân nào.'?>  
                    <?= Html::a('Danh sách', ['sub-pitch/list-booking', 'id' => $subPitch->sub_pitch_id, 
                    'sort' => '-created_at'], ['class' => 'btn btn-hero btn-sm ']) ?>
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
