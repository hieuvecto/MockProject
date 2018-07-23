<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\Utils;

$owner = \Yii::$app->owner->identity;
?>

<header class="main-header">

  <!-- Logo -->
  <a href="#" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>TS</b>ol</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><?= Yii::$app->name ?></span>
  </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-envelope-o"></i>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have 0 messages</li>
            <li class="footer"><a href="#">See All Messages</a></li>
          </ul>
        </li>
        <!-- Notifications: style can be found in dropdown.less -->
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have 0 notifications</li>
            <li class="footer"><a href="#">View all</a></li>
          </ul>
        </li>
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?= Utils::catchIdentityImg(Utils::imgSrc($owner->avatar_url), [
                          'class' => 'user-image',
                          'alt' => 'Owner Image',
                        ]) 
                      ?>
            <span class="hidden-xs"><?= $owner->email ?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <?= Utils::catchIdentityImg(Utils::imgSrc($owner->avatar_url), [
                          'class' => 'img-circle',
                          'alt' => 'Owner Image',
                        ]) 
                      ?>

              <p>
                <?= $owner->email ?>
                <small>Tài khoản từ <?= date('Y / m', $owner->created_at) ?></small>
              </p>
            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <?= Html::a('Profile', ['/owner/view', 'id' => $owner->owner_id], [
                    'class' => 'btn btn-default btn-flat', 
                  ]) ?>
              </div>
              <div class="pull-right">
                <?= Html::a('Đăng xuất', ['/owner/logout'], [
                    'class' => 'btn btn-default btn-flat', 
                  ]) ?>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>

  </nav>
</header>