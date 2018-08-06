<?php
use yii\helpers\Html;

$this->title = 'Đặt sân tại chỗ';
?>
<div class="container list-rps">
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-futbol-o"></i> Quản lý sân</a></li>
        <li><?= Html::a('Danh sách', ['pitch/index', 'sort' => '-created_at']) ?></a></li>
        <li><?= Html::a('Chi tiết sân', ['pitch/view', 'id' => $subPitch->pitch_id]) ?></li>
        <li class="active">Đặt sân tại chỗ</li>
      </ol>
    </section>

    <?= $this->render('//booking/_form', [
        'model' => $model,
        'subPitch' => $subPitch,
        'pitch' => $pitch,
        'class' => 'm-t-30 m-b-30',
        'page' => 'owner',
    ]) ?>
</div>

<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div id='calendar'></div>
            </div>       
        </div>

    </div>
</div>