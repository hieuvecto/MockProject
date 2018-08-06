<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\helpers\Utils;
use janisto\timepicker\TimePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Danh sách đặt sân của' . ' ' . $subPitch->name;
$this->params['breadcrumbs'][] = $this->title;

$this->registerJS('
$(".input-group-addon").on("click", function() {
    var input = $(this).next();

    if (input.val().trim() !== "") {
        console.log("OK");
        input.val("");
        input.trigger("change");
        var e = $.Event("keydown");
        e.which = 13;
        input.trigger(e);
    }
    
});

$(document).on("pjax:success", function() {
    var addon = $(".input-group-addon");
    addon.off("click");
    addon.on("click", function() {
        var input = $(this).next();

        if (input.val().trim() !== "") {
            console.log("OK");
            input.val("");
            input.trigger("change");
            var e = $.Event("keydown");
            e.which = 13;
            input.trigger(e);
        }
    });

    $("#dashboard-grid-reset").click(function()
    {   
        console.log("Test");
        var id="list-booking-grid";
        var inputSelector="#"+id+ " .filters input, "+"#"+id+" .filters select";
        $(inputSelector).each( function(i,o) {
            $(o).val("");
            $(o).trigger("change");
        });
        
        var e = $.Event("keydown");
        e.which = 13;
        $($(inputSelector)[0]).trigger(e);
    });
});

$("#dashboard-grid-reset").click(function()
{   
    console.log("Test");
    var id="list-booking-grid";
    var inputSelector="#"+id+ " .filters input, "+"#"+id+" .filters select";
    $(inputSelector).each( function(i,o) {
        $(o).val("");
        $(o).trigger("change");
    });
    
    var e = $.Event("keydown");
    e.which = 13;
    $($(inputSelector)[0]).trigger(e);
});

');

?>
<div class="container">

    <section class="content-header">
      <h1 class="title" style="width: 40%;">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-futbol-o"></i> Quản lý sân</a></li>
        <li><?= Html::a('Danh sách', ['pitch/index', 'sort' => '-created_at']) ?></a></li>
        <li><?= Html::a('Chi tiết sân', ['pitch/view', 'id' => $subPitch->pitch_id]) ?></li>
        <li class="active">Danh sách đặt sân</li>
      </ol>
    </section>

    <?php Pjax::begin([
        'options' => [
            'class' => 'table-responsive box table-box',
        ]]); ?>
        <?= GridView::widget([
            'id' => 'list-booking-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Ngày đặt sân',
                    'attribute' => 'book_day',
                    'filter' => TimePicker::widget([
                        //'language' => 'fi',
                        'name' => "BookingSearch[book_day]",
                        'value' => $searchModel->book_day,
                        'mode' => 'date',
                        'addon' => $searchModel->book_day ? 
                        '<i class="fa fa-times" aria-hidden="true"></i>':
                        '<i class="glyphicon glyphicon-calendar"></i>',
                        'clientOptions' => [
                            'dateFormat' => 'yy-mm-dd',
                        ]
                    ]),
                    'headerOptions' => ['style' => 'width:15%'],
                ],
                [
                    'label' => 'Bắt đầu lúc',
                    'attribute' => 'start_time',
                    'format' => ['time', 'php:H:i'],
                    'filter' => Utils::getTimeArray(),
                    'headerOptions' => ['style' => 'width:10%'],
                ],
                [
                    'label' => 'Kết thúc lúc',
                    'attribute' => 'end_time',
                    'format' => ['time', 'php:H:i'],
                    'filter' => Utils::getTimeArray(),
                    'headerOptions' => ['style' => 'width:10%'],
                ],
                [   
                    'label' => 'Thanh toán?',
                    'attribute' => 'is_verified',
                    'format' => 'raw',
                    'value' => function($data) 
                    {
                        return $data->is_verified ? 
                        '<i class="fa fa-check color-success" aria-hidden="true"></i>' : 
                        '<i class="fa fa-times color-danger" aria-hidden="true"></i>';
                    },
                    'filter' => [
                            0 => 'No',
                            1 => 'Yes',
                        ]
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
                    'filter' => '',
                ],
                [
                    'label' => 'Lần cuối cập nhật',
                    'attribute' => 'updated_at',
                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    'filter' => '',
                ],
                [
                        'format'=>'raw',
                        'value' => function($data) 
                        {   
                            $label = '<span class="glyphicon glyphicon-eye-open"></span>';
                            $typeBtn = 'btn-hero';
                            $title = 'Xem';

                            if (!$data->is_verified) {
                                $label = '<i class="fa fa-credit-card"></i>';
                                $typeBtn = 'btn-primary';
                                $title = 'Thanh toán';
                            }             

                            $btnStr =  Html::a($label, 
                                [
                                    'view-booking', 
                                    'booking_id' => $data->booking_id
                                ], 
                                ['class' => 'btn btn-sm ' . $typeBtn,
                                    'title' => $title,
                                ]);

                            if ($data->is_verified == 0)
                                $btnStr = $btnStr . ' ' .
                                Html::a('<i class="fa fa-trash"></i>', ['delete-booking', 'booking_id' => $data->booking_id], [
                                    'class' => 'btn btn-danger btn-sm',
                                    'title' => 'Xóa',
                                    'data' => [
                                        'confirm' => 'Bạn có chắc xóa đặt sân này?',
                                        'method' => 'post',
                                    ],
                                ]);

                            return $btnStr;
                        },
                        'headerOptions' => ['style' => 'width:9%;'],
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

                
  