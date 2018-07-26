<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\helpers\Utils;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Thông tin chủ sân';
?>
<div class="container">
    <h1 class="title"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->owner_id], ['class' => 'btn btn-hero btn-md']) ?>
        <?= Html::a('Đổi mật khẩu', ['change-password', 'id' => $model->owner_id], ['class' => 'btn btn-hero btn-md']) ?>
        <?php if ($socials['facebook']['is_render']): ?>
        <a href="/owner/auth?authclient=facebook" class="login100-social-item bg1 inline-flex" title="Facebook">
            <i class="fa fa-facebook"></i>
        </a>
        <?php endif; ?>
        <?php if ($socials['twitter']['is_render']): ?>
        <a href="#" class="login100-social-item bg2 inline-flex">
            <i class="fa fa-twitter"></i>
        </a>
        <?php endif; ?>
        <?php if ($socials['google']['is_render']): ?>
        <a href="/owner/auth?authclient=google" class="login100-social-item bg3 inline-flex">
            <i class="fa fa-google"></i>
        </a>
        <?php endif; ?>
    </p>

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <img class="img-fluid" src="<?= Utils::catchIdentitySrc(Utils::imgSrc($model->avatar_url)) ?>">
            </div>
        </div>
        <div class="col-md-7 custom-box p-tb-15">
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
