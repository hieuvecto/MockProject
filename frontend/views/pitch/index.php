<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Pitches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pitch-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Pitch', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                [
                    'attribute' => 'description',
                    'value' => function($dataProvider) {
                        return substr($dataProvider->description, 0, 20) . '...';
                    }
                ],
                'city',
                'district',
                'street',
                'apartment_number',
                'phone_number',
                [
                    'label' => 'Unverified Booking',
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
                'created_at:datetime',
                'updated_at:datetime',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
