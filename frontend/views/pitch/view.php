<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $pitch common\models\Pitch */

$this->title = $pitch->name;
$this->params['breadcrumbs'][] = ['label' => 'Pitches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pitch-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $pitch->pitch_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $pitch->pitch_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Extend', ['extend', 'id' => $pitch->pitch_id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $pitch,
        'attributes' => [
            [
                'attribute'=>'avatar_url',
                'value'=>$pitch->avatar_url,
                'format' => ['image',['width'=>'500','height'=>'300']],
            ],
            'name',
            'description:ntext',
            'city',
            'district',
            'street',
            'apartment_number',
            'phone_number',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'Start Time',
                'value' => $subPitch->start_time
            ],
            [
                'attribute' => 'End Time',
                'value' => $subPitch->end_time
            ],
            [
                'attribute' => 'Price Per Hour',
                'value' => $subPitch->price_per_hour . ' ' . $subPitch->currency
            ],
            [
                'attribute' => 'Status',
                'value' => $subPitch->status ? 'free' : 'busy'
            ],
        ],
    ]) ?>

</div>
