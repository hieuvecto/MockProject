<?php

use yii\helpers\Url;
use common\helpers\Utils;

$owner = \Yii::$app->owner->identity;
?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar" style="height: auto;">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <?= Utils::catchIdentityImg(Utils::imgSrc($owner->avatar_url), [
            'class' => 'img-circle',
            'alt' => 'Owner Image',
          ]) 
        ?>
      </div>
      <div class="pull-left info">
        <p><?= $owner->email ?></p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu tree" data-widget="tree">
      <li class="header">THANH ĐIỀU HƯỚNG</li>
      <li class="<?= Utils::isRoute('owner/dashboard') ?>">
        <a href="<?= Url::to('/owner/dashboard') ?>">
          <i class="fa fa-dashboard"></i> <span>Bảng điều khiển</span>
        </a>
      </li>
      <li class="treeview <?= Utils::isController('pitch') ?> <?= Utils::isController('sub-pitch') ?>">
        <a href="#">
          <i class="fa fa-futbol-o"></i> <span>Quản lý sân</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?= Utils::isRoute('pitch/index') ?>">
            <a href="<?= Url::to(['/pitch/index']) ?>">
              <i class="fa fa-list-alt"></i> Danh sách
            </a>
          </li>
          <li class="<?= Utils::isRoute('pitch/create') ?>">
            <a href="<?= Url::to(['/pitch/create']) ?>">
              <i class="fa fa-plus"></i> Tạo sân
            </a>
          </li>
        </ul>
      </li>

      <li class="treeview <?= Utils::isController('campaign') ?>">
        <a href="#">
          <i class="fa fa-shopping-bag"></i> <span>Quản lý khuyến mãi</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?= Utils::isRoute('campaign/index') ?>">
            <a href="<?= Url::to(['/campaign/index']) ?>">
              <i class="fa fa-list-alt"></i> Danh sách
            </a>
          </li>
          <li class="<?= Utils::isRoute('campaign/create') ?>">
            <a href="<?= Url::to(['/campaign/create']) ?>">
              <i class="fa fa-plus"></i> Tạo khuyến mãi
            </a>
          </li>
        </ul>
      </li>

      <li class="treeview <?= Utils::isController('owner', 'owner/dashboard') ?>">
        <a href="#">
          <i class="fa fa-user"></i> <span>Quản lý tài khoản</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="<?= Utils::isRoute('owner/view') ?>">
            <a href="<?= Url::to(['/owner/view', 'id' => \Yii::$app->owner->identity->owner_id ]) ?>">
              <i class="fa fa-info-circle"></i> Thông tin
            </a>
          </li>
          <li class="<?= Utils::isRoute('owner/update') ?>">
            <a href="<?= Url::to(['/owner/update', 'id' => \Yii::$app->owner->identity->owner_id ]) ?>">
              <i class="fa fa-pencil-square-o"></i> Cập nhật
            </a>
          </li>
          <li class="<?= Utils::isRoute('owner/change-password') ?>">
            <a href="<?= Url::to(['/owner/change-password', 'id' => \Yii::$app->owner->identity->owner_id ]) ?>">
              <i class="fa fa-key"></i> Đổi mật khẩu
            </a>
          </li>
        </ul>
      </li>
    </ul>
    </section>
    <!-- /.sidebar -->
  </aside>