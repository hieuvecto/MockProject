<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Thông tin người dùng';
?>
<div class="container m-t-80 m-b-50">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->user_id], ['class' => 'btn btn-hero btn-md']) ?>
        <?= Html::a('Đổi mật khẩu', ['change-password', 'id' => $model->user_id], ['class' => 'btn btn-hero btn-md']) ?>
    </p>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <img class="img-fluid" src="<?= $model->avatar_url ? Utils::imgSrc($model->avatar_url) : '/images/gravatar.png' ?>">
            </div>
        </div>
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'email:email',
                    [
                        'label' => 'Số điện thoại',
                        'attribute' => 'phone',
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
    </div>

</div>
