<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

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
        ],
    ]) ?>

    <h1>Sub pitches</h1>

    <?php foreach ($subPitches as $model): ?>
        <p>
            <?= Html::a('Update', ['sub-pitch/update', 'id' => $model->sub_pitch_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['sub-pitch/delete', 'id' => $model->sub_pitch_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
             You have <?= $model->getBookings(['is_verified' => 0])->count() ?> unverified booking. Verify it
            <?= Html::a('Verify', ['sub-pitch/list-booking', 'id' => $model->sub_pitch_id], ['class' => 'btn btn-info']) ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'id' => 'sub-pitch-' . $model->sub_pitch_id,
            'attributes' => [
                [
                    'attribute'=>'avatar_url',
                    'value'=>$model->avatar_url,
                    'format' => ['image',['width'=>'500','height'=>'300']],
                ],
                'name',
                'description:ntext',
                'status',
                'start_time',
                'end_time',
                [
                    'attribute' => 'Price Per Hour',
                    'value' => $model->price_per_hour . ' ' . $model->currency
                ],
                [
                    'label' => 'Verified Bookings',
                    'value' => $model->getBookings(['is_verified' => 1])->count(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    <?php endforeach ?>

</div>
