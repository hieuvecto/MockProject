<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $pitch common\models\Pitch */

$this->title = $model->name;

$applyStr = '';

foreach ($subPitches as $subPitch) {
    $applyStr = $applyStr . '<p>' .
                Html::a($subPitch->name, ['sub-pitch/view', 'id' => $subPitch->sub_pitch_id])
                . '</p>';
}

?>
<div class="container m-b-50">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-md-5 custom-box p-tb-15">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Tên khuyến mãi',
                        'attribute' => 'name',
                    ],
                    [   
                        'label' => 'Mô tả',
                        'attribute' => 'description',
                        'format' => 'text',
                    ],
                    [
                        'label' => 'Bắt đầu lúc',
                        'attribute' => 'start_time',
                        'format' =>['datetime', 'php:Y-m-d h:i:s']
                    ],
                    [
                        'label' => 'Kết thúc lúc',
                        'attribute' => 'end_time',
                        'format' => ['datetime', 'php:Y-m-d h:i:s']
                    ],
                    [
                        'label' => 'Kiểu khuyến mãi',
                        'attribute' => 'type',
                        'value' => function($data) {
                            switch($data->type) {
                                case 0:
                                    return 'Giảm giá %';
                                    break;
                                default:
                                    return 'Không xác định';
                            }
                        }
                    ],
                    [
                        'label' => 'Giá trị',
                        'attribute' => 'value',
                        'value' => function($data) {
                            switch($data->type) {
                                case 0:
                                    return $data->value . '%';
                                    break;
                                default:
                                    return 'Không xác định';
                            }
                        }
                    ],
                    [
                        'label' => 'Áp dụng cho',
                        'format' => 'raw',
                        'value' => $applyStr,
                    ],
                    [
                        'label' => 'Tạo lúc',
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    ],
                    [
                        'label' => 'Lần cuối cập nhật',
                        'attribute' => 'updated_at',
                        'format' => ['datetime', 'php:Y-m-d H:i:s'],
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-md-7">
            <div class="card">
                <?= Utils::catchImg(Utils::imgSrc($model->avatar_url), [
                    'class' => 'img-fluid',
                ]) ?>
            </div>
        </div>
    </div>

</div>
