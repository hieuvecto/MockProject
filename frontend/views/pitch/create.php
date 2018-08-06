<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Tạo sân';
$pitchForm->SubPitch->currency = 'VND';

?>
<div class="container">

    <section class="content-header">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-futbol-o"></i> Quản lý sân</a></li>
        <li class="active">Tạo sân</li>
      </ol>
    </section>

    <?= $this->render('_form', [
        'pitchForm' => $pitchForm,
    ]) ?>

</div>
