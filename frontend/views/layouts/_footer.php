<?php

use yii\helpers\Html;

?>

<div class="contact-footer">
  <div class="container">
    <div class="row" style="margin: 10px auto 20px auto">
      <div class="col-md-8">
        <h5 class="grey-text text-darken-2">Liên hệ</h5>
        <p>
          <a href="#" class="orange-text"><i class="fa fa-facebook fa-2x"></i></a>&nbsp;&nbsp;
          <a class="orange-text" href="#"><i class="fa fa-twitter fa-2x"></i></a>&nbsp;&nbsp;
          <a class="orange-text" href="#"><i class="fa fa-google fa-2x"></i></a>&nbsp;&nbsp;
        </p>
      </div>
      <div class="col-md-4">
        <h5 class="grey-text text-darken-2">Về Tìm Sân Online</h5>
        <ul>
          <li><a class="orange-text" href="#">Giới thiệu</a></li>
          <li><a class="orange-text" href="#">Điều khoản sử dụng</a></li>
          <li><a class="orange-text" href="#">Chính sách bảo mật</a></li>
          <li><a class="orange-text" href="#">Blog</a></li>
          <li><a class="orange-text" href="#!">Về chúng tôi</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="footer-copyright">
  <div class="container">
    <div class="row" style="margin-bottom: 0px">
      <div class="col l6 s12 ">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></div>
    </div>
  </div>
</div>