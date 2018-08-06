<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Pitch */

$this->title = 'Cập nhật khuyến mãi';

?>
<div class="container">

    <section class="content-header">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-shopping-bag"></i> Quản lý khuyến mãi</a></li>
        <li><?= Html::a('Danh sách', ['campaign/index', 'sort' => '-created_at']) ?></a></li>
        <li><?= Html::a('Chi tiết', ['campaign/view', 'id' => $campaignForm->Campaign->campaign_id]) ?></li>
        <li class="active">Cập nhật</li>
      </ol>
    </section>

    <?= $this->render('_form', [
        'campaignForm' => $campaignForm,
        'subPitches' => $subPitches,
    ]) ?>

</div>
