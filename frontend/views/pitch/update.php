<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Update Pitch';
$this->params['breadcrumbs'][] = ['label' => 'Pitches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$pitchForm->SubPitch->currency = 'VND';

?>
<div class="pitch-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'pitchForm' => $pitchForm,
    ]) ?>

</div>
