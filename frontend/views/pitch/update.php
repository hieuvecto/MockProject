<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Cập nhật sân';
?>
<div class="container">

    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'pitchForm' => $pitchForm,
    ]) ?>

</div>
