<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $is_verified ? 'List Verified Bookings: ' : 'List Unverified Bookings: ';
$this->title = $this->title . ' ' . $subPitch->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'book_day',
                'start_time',
                'end_time',
                [
                    'attribute' => 'message',
                    'value' => function($dataProvider) {
                        return substr($dataProvider->message, 0, 20) . '...';
                    }
                ],
                [
                    'attribute' => 'is_verified',
                    'value' => function($data) 
                    {
                        return $data->is_verified ? 'Yes' : 'No';
                    }
                ],
                'total_price',
                'created_at:datetime',
                'updated_at:datetime',
                [
                        'format'=>'raw',
                        'value' => $is_verified ? 
                        function($data)
                        {   
                            return Html::a('View', 
                                [
                                    'view-booking', 
                                    'booking_id' => $data->booking_id
                                ], 
                                ['class' => 'btn btn-primary']);
                        } :
                        function($data)
                        {   
                            return Html::a('Verify', 
                                [
                                    'view-booking', 
                                    'booking_id' => $data->booking_id
                                ], 
                                ['class' => 'btn btn-primary']);
                        },
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
