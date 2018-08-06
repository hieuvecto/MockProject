<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Khuyến mãi của tôi';

$this->registerJS('
$(document).on("pjax:success", function() {
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

    <section class="content-header">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-shopping-bag"></i> Quản lý khuyến mãi</a></li>
        <li class="active">Danh sách</li>
      </ol>
    </section>

    <p>
        <?= Html::a('Tạo khuyến mãi', ['create'], ['class' => 'btn btn-hero btn-md']) ?>
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
                    'label' => 'Tên khuyến mãi',
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function($data)
                    {   
                        return Html::a($data->name, 
                                ['view','id'=> $data->campaign_id], 
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
                    'label' => 'Bắt đầu lúc',
                    'attribute' => 'start_time',
                    'filter' => '',
                    'headerOptions' => ['style' => 'width:15%'],
                ],
                [
                    'label' => 'Kết thúc lúc',
                    'attribute' => 'end_time',
                    'filter' => '',
                    'headerOptions' => ['style' => 'width:15%'],
                ],
                [
                    'label' => 'Kiểu khuyến mãi',
                    'attribute' => 'type',
                    'filter' => '',
                    'value' => function($data) {
                        switch ($data->type) {
                            case 0:
                                return 'Giảm giá %';
                            default:
                                return 'Không xác định';
                        }
                    }
                ],
                [
                    'label' => 'Giá trị',
                    'attribute' => 'value',
                    'value' => function($data) {
                        switch ($data->type) {
                            case 0:
                                return $data->value . '%';
                            default:
                                return 'Không xác định';
                        }
                    }
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
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
