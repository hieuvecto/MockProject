<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use janisto\timepicker\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Booking */

$this->title = 'Create Booking';
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-create">

    <h1><?= Html::encode($this->title . ' ' . $subPitch->name) ?></h1>

    <div class="booking-form">

    <?= $this->render('_form', [
        'model' => $model,
        'subPitch' => $subPitch,
    ]) ?>
</div>

</div>
