<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dashboard';
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
                [
                        'label'=>'Pitch Place',
                        'format'=>'raw',
                        'value' => function($data)
                        {   
                            $pitch = $data->getSubPitch()->one()
                                        ->getPitch()->one();
                            return Html::a($pitch->name, 
                                    ['view-pitch','pitch_id'=> $pitch->pitch_id], 
                                    ['title' => 'View','class'=>'no-pjax']);
                        }
                ],
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
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
