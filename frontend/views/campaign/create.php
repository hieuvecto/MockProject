<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Tạo khuyến mãi';

?>
<div class="container">

    <section class="content-header">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-shopping-bag"></i> Quản lý khuyến mãi</a></li>
        <li class="active">Tạo khuyến mãi</li>
      </ol>
    </section>

    <?= $this->render('_form', [
        'campaignForm' => $campaignForm,
        'subPitches' => $subPitches,
    ]) ?>

</div>
