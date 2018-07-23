<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$user = \Yii::$app->user->identity;
$owner = \Yii::$app->owner->identity;
?>

<div class="list-group for-mobile" id="sidebar">
  <span href="#" class="list-group-item active">
      <i class="fa fa-caret-down" aria-hidden="true"></i>
      <span class="pull-right" id="slide-submenu">
          <i class="fa fa-times"></i>
      </span>
  </span>
  <?= Html::a('Đăng ký sân', ['site/welcome-owner'],
                        ['class' => 'list-group-item'] ) ?>
  <?php if (isset($user)):?>
    <?= Html::a('Tìm và đặt sân', ['booking/pitches'], 
      ['class' => 'list-group-item']) ?>

    <?= Html::a('Danh sách đặt sân', ['booking/dashboard'], 
      ['class' => 'list-group-item']) ?>
 
    <?= Html::a('Profile', ['user/view', 'id' => $user->user_id ], 
      ['class' => 'list-group-item']) ?>
  
    <?= Html::a('Đăng xuất', ['user/logout'], 
      ['class' => 'list-group-item']) ?>
  <?php else: ?>
    <?= Html::a('Đăng nhập', ['user/login'], 
      ['class' => 'list-group-item']) ?>
  <?php endif; ?>
</div>

<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <div class="mini-submenu for-mobile"">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </div>

            <a class="navbar-brand" rel="home" href="/index.php" title="Tìm Sân Online">
                <img style="max-width:37px; margin-top: -7px;"
                     src="/images/football-2-xxl.png">  
            </a>
            <a class="navbar-brand brand-text" rel="home" href="/index.php" title="Tìm Sân Online">
                Tìm Sân Online
            </a>
        </div>
        
        <div id="navbar" class="collapse navbar-collapse navbar-responsive-collapse">
            <form class="navbar-form navbar-left" role="search" method="get" id="searchform" action="/booking/pitches">
                <div class="form-group">
                    <input name="PitchSearch[keyword]" id="pitchsearch-keyword" type="text" class="search-query form-control" autocomplete="off" placeholder="Nhập tên hoặc địa chỉ để tìm sân..." value="<?= isset($this->params['keyword']) ? $this->params['keyword'] : '' ?>">
                </div>
            </form>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?= Html::a('Đăng ký sân', ['site/welcome-owner'],
                        ['class' => 'navbar-a'] ) ?>
                </li>
                <?php if (isset($user)):?>
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                      <li>
                        <?= Html::a('Tìm và đặt sân', ['booking/pitches']) ?>
                      </li>
                      <li>
                        <?= Html::a('Danh sách đặt sân', ['booking/dashboard']) ?>
                      </li>
                      <li class="divider"></li>
                      <li>
                        <?= Html::a('Profile', ['user/view', 'id' => $user->user_id ]) ?>
                      </li>
                      <li>
                        <?= Html::a('Đăng xuất', ['user/logout']) ?>
                      </li>
                  </ul>                 
                </li>
                <?php else: ?>
                <li>
                  <?= Html::a('Đăng nhập', ['user/login']) ?>
                </li>
                <?php endif; ?>
            </ul>
            
        </div>

    </div>
</div>