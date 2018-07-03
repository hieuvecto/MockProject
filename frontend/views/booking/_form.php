<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use janisto\timepicker\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Booking */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'book_day')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'date',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd',
                ]
            ]) ?>

    <?= $form->field($model, 'start_time')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'time',
                'clientOptions' => [
                    'hour' => date('H'),
                    'minute' => date('i'),
                ]
            ]) ?>

    <?= $form->field($model, 'end_time')
            ->widget(TimePicker::className(), [
                //'language' => 'fi',
                'mode' => 'time',
                'clientOptions' => [
                    'hour' => date('H'),
                    'minute' => date('i'),
                ]
            ]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

<h2> Pitch Detail</h2>
<?= DetailView::widget([
    'model' => $subPitch,
    'attributes' => [
        'name',
        'description:ntext',
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
            'label' => 'Current Status',
            'value' => $subPitch->status ? 'free' : 'busy'
        ],
        'created_at:datetime',
        'updated_at:datetime',
    ],
]) ?>
