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

    <?= DetailView::widget([
        'model' => $pitch,
        'attributes' => [
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
            <?= Html::a('Book', ['create', 'sub_pitch_id' => $model->sub_pitch_id], ['class' => 'btn btn-primary']) ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'id' => 'sub-pitch-' . $model->sub_pitch_id,
            'attributes' => [
                'name',
                'description:ntext',
                'status',
                'start_time',
                'end_time',
                [
                    'attribute' => 'Price Per Hour',
                    'value' => $model->price_per_hour . ' ' . $model->currency
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    <?php endforeach ?>

</div>
