<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Update Pitch: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pitches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->pitch_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pitch-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
