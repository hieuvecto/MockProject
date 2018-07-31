<?php

$this->title = 'Đặt sân tại chỗ';
?>
<div class="container list-rps">
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