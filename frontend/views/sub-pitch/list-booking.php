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

    <h1 class="title"><?= Html::encode($this->title) ?></h1>

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
                    'label' => 'Xác nhận?',
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
                    'label' => 'Thanh toán?',
                    'attribute' => 'is_paid',
                    'format' => 'raw',
                    'value' => function($data) 
                    {
                        return $data->is_paid ? 
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
                            $label = 'Xem';
                            $typeBtn = 'btn-primary';

                            if ($data->is_verified && !$data->is_paid) {
                                $label = 'Thanh toán';
                                $typeBtn = 'btn-hero';
                            }
                            elseif (!$data->is_verified) {
                                $label = 'Xác nhận';
                                $typeBtn = 'btn-info';
                            }
                            

                            return Html::a($label, 
                                [
                                    'view-booking', 
                                    'booking_id' => $data->booking_id
                                ], 
                                ['class' => 'btn btn-sm ' . $typeBtn]);
                        }
            
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

                
  