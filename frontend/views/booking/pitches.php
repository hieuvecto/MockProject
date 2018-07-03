<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pitches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pitch-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                        'attribute'=>'name',
                        'format'=>'raw',
                        'value' => function($data)
                        {
                            return Html::a($data->name, 
                                    ['view-pitch','pitch_id'=> $data->pitch_id], 
                                    ['title' => 'View','class'=>'no-pjax']);
                        }
                ],
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
                'created_at:datetime',
                'updated_at:datetime',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>
