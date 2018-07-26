<?php

$this->title = 'Thay đổi đặt sân';
?>
<div class="container list-rps">
    <?= $this->render('_form', [
        'model' => $model,
        'subPitch' => $subPitch,
        'pitch' => $pitch,
        'class' => 'backdrop-booking',
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