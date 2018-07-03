<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Booking */

$this->title = 'Booking Detail';
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->booking_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->booking_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Booked User',
                'value' => $user->email,
            ],
            'book_day',
            'start_time',
            'end_time',
            'message:ntext',
            [
                'attribute' => 'is_verified',
                'value' => $model->is_verified ? 'Yes' : 'No',
            ],
            [
                'attribute' => 'total_price',
                'value' => $model->total_price . ' ' . $subPitch->currency,
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <h2>Booked Place Detail</h2>
    <?= DetailView::widget([
        'model' => $pitch,
        'attributes' => [
            'name',
            'city',
            'district',
            'street',
            'apartment_number',
            'phone_number',
        ],
    ]) ?>

    <h2>Pitch Detail</h2>
    <?= DetailView::widget([
        'model' => $subPitch,
        'attributes' => [
            'name',
            [
                'attribute' => 'price_per_hour',
                'value' => $subPitch->price_per_hour . ' ' . $subPitch->currency,
            ],
            [
                'attribute' => 'status',
                'label' => 'Current Status',
            ],
            'start_time:datetime',
            'end_time:datetime',
        ],
    ]) ?>

</div>
