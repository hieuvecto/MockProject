<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Cập nhật sân';
?>
<div class="container">

    <section class="content-header">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-futbol-o"></i> Quản lý sân</a></li>
        <li><?= Html::a('Danh sách', ['pitch/index', 'sort' => '-created_at']) ?></a></li>
        <li><?= Html::a('Chi tiết sân', ['pitch/view', 'id' => $pitchForm->Pitch->pitch_id]) ?></li>
        <li class="active">Cập nhật sân</li>
      </ol>
    </section>

    <?= $this->render('_form', [
        'pitchForm' => $pitchForm,
    ]) ?>

</div>
