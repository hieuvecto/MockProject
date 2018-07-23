<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sân của tôi';

$this->registerJS('
$(document).on("pjax:success", function() {
    $("#dashboard-grid-reset").click(function()
    {   
        console.log("Test");
        var id="dashboard-grid";
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
    var id="index-grid";
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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Tạo sân', ['create'], ['class' => 'btn btn-hero btn-md']) ?>
    </p>

    <?php Pjax::begin([
        'options' => [
            'class' => 'table-responsive box table-box',
        ]]); ?>
        <?= GridView::widget([
            'id' => 'index-grid',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
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
                    'value' => function($dataProvider) {
                        return substr($dataProvider->description, 0, 20) . '...';
                    }
                ],
                [
                    'label' => 'Thành phố/Tỉnh',
                    'attribute' => 'city',
                    'filter' => [
                        'Đà Nẵng' => 'Đà Nẵng'
                    ],
                ],
                [
                    'label' => 'Quận/Huyện',
                    'attribute' => 'district',
                    'filter' => [
                        'Cẩm Lệ' => 'Cẩm Lệ',
                        'Hải Châu' => 'Hải Châu',
                        'Hòa Vang' => 'Hòa Vang',
                        'Liên Chiểu' => 'Liên Chiểu',
                        'Ngũ Hành Sơn' => 'Ngũ Hành Sơn',
                        'Sơn Trà' => 'Sơn Trà',
                        'Thanh Khê' => 'Thanh Khê',
                    ],
                ],
                [
                    'label' => 'Địa chỉ',
                    'attribute' => 'address',
                ],
                [
                    'label' => 'Chưa xác nhận?',
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
                    'headerOptions' => ['class' => 'title'],
                ],
                [
                    'label' => 'Tạo lúc',
                    'attribute' => 'created_at',
                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    'filter' => '',
                    'headerOptions' => ['style' => 'width:15%'],
                ],
                [
                    'label' => 'Lần cuối cập nhật',
                    'attribute' => 'updated_at',
                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    'filter' => '',
                    'headerOptions' => ['style' => 'width:15%'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '<i id="dashboard-grid-reset" class="fa fa-refresh" aria-hidden="true"></i>',
                    'headerOptions' => ['style' => 'width:8%'],
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
