<?php
use yii\helpers\Html;
use common\helpers\Utils;
/* @var $this yii\web\View */

$this->title = 'Tìm Sân Online | Tìm kiếm và đặt sân bóng Online';
$this->registerJS("  
$(document).ready(function($) {
 
        $('#myCarousel').carousel({
                interval: 5000
        });
 
        $('#carousel-text').html($('#slide-content-0').html());
 
        //Handles the carousel thumbnails
       $('[id^=carousel-selector-]').click( function(){
            var id = this.id.substr(this.id.lastIndexOf('-') + 1);
            var id = parseInt(id);
            $('#myCarousel').carousel(id);
        });
 
 
        // When the carousel slides, auto update the text
        $('#myCarousel').on('slid.bs.carousel', function (e) {
                var id = $('.active.item.item-cp').attr('data-slide-number');
                $('#carousel-text').html($('#slide-content-'+id).html());
        });
});
");

\Yii::info($campaigns, 'campaigns');
?>

<div class="container-fluid">
  <div class="carousel fade-carousel slide" data-ride="carousel" data-interval="4000" id="bs-carousel">
    <!-- Overlay -->

    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#bs-carousel" data-slide-to="0" class="active"></li>
      <li data-target="#bs-carousel" data-slide-to="1"></li>
      <li data-target="#bs-carousel" data-slide-to="2"></li>
    </ol>
    
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <div class="item slides active">
        <div class="slide-1">
            <div class="overlay"></div>
        </div>
        <div class="hero">
          <hgroup>
              <h1>Tra cứu thông tin sân bóng</h1>
              <h3 class="for-mobile">theo địa điểm, giá sân,...</h3>
              <h3 class="not-mobile">theo địa điểm, giá sân,... ở gần vị trí của bạn</h3>
          </hgroup>
          <?= Html::a('Tra cứu ngay', ['booking/pitches'], ['class' => 'btn btn-hero btn-lg']) ?>
        </div>
      </div>
      <div class="item slides">
        <div class="slide-2">
            <div class="overlay"></div>
        </div>
        <div class="hero">        
          <hgroup>
              <h1>Đặt sân online</h1>        
              <h3>
              Nhanh chóng và tiện lợi
              </h3>
          </hgroup>       
          <?= Html::a('Đặt sân ngay', ['booking/pitches'], ['class' => 'btn btn-hero btn-lg']) ?>
        </div>
      </div>
      <div class="item slides">
        <div class="slide-3">
            <div class="overlay"></div>
        </div>
        <div class="hero">        
          <hgroup>
              <h1>Quản lý sân bóng </h1>    
              <h3 class="for-mobile">theo cách chuyên nghiệp</h3>    
              <h3 class="not-mobile">theo cách chuyên nghiệp với nhiều tính năng và biểu đồ trực quan</h3>
          </hgroup>
          <?= Html::a('Đăng ký sân ngay', ['owner/dashboard'], ['class' => 'btn btn-hero btn-lg']) ?>
        </div>
      </div>
    </div> 
  </div>
</div>

<section class="banner-sec">
  <div class="container m-t-20 m-b-20">
    <div id="main_area">
          <!-- Slider -->
          <div class="row">
              <div class="col-xs-12" id="slider">
                  <!-- Top part of the slider -->
                  <div class="row">
                      <div class="col-sm-8" id="carousel-bounding-box">
                          <div class="carousel slide" id="myCarousel">
                              <!-- Carousel items -->
                              <div class="carousel-inner">
                                <?php foreach($campaigns as $key => $campaign): ?>
                                  <div class="<?= $key === 0 ? 'active' : '' ?> item item-cp" data-slide-number="<?= $key ?>">
                                  <img class="img-carousel-inner" src="<?= Utils::catchImgSrc(Utils::imgSrc($campaign->avatar_url)) ?>">
                                  </div>

                                <?php endforeach; ?>
                              </div><!-- Carousel nav -->
                              <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                  <span class="glyphicon glyphicon-chevron-left"></span>                                       
                              </a>
                              <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                  <span class="glyphicon glyphicon-chevron-right"></span>                                       
                              </a>                                
                              </div>
                      </div>

                      <div class="col-sm-4" id="carousel-text"></div>

                      <div id="slide-content" style="display: none;">
                        <?php foreach($campaigns as $key => $campaign): ?>
                          <div id="slide-content-<?= $key ?>">
                              <h2><?= $campaign->name ?></h2>
                              <p><?php 
                                if (strlen($campaign->description) > 100) {
                                  $sub_str = substr($campaign->description, 0, 100);
                                  echo $substr . '...';
                                }
                                else
                                  echo $campaign->description;
                              ?></p>
                              <p><?php 
                                switch ($campaign->type) {
                                  case 0:
                                    echo 'Giảm giá' . ' ' . $campaign->value . '%';
                                    break;
                                  
                                  default:
                                    echo 'Không xác định';
                                    break;
                                }
                              ?>
                              </p>
                              <p class="sub-text"><?= $campaign->start_time ?> - <?= $campaign->end_time ?>
                                <?= Html::a('Xem chi tiết', ['campaign/view-public', 'id' => $campaign->campaign_id]) ?>
                              </p>
                          </div>

                        <?php endforeach; ?>
                      </div>
                  </div>
              </div>
          </div><!--/Slider-->

          <div class="row hidden-xs" id="slider-thumbs">
                  <!-- Bottom switcher of slider -->
                  <ul class="hide-bullets">
                    <?php foreach($campaigns as $key => $campaign): ?>
                      <li class="col-sm-2">
                          <a class="thumbnail" id="carousel-selector-<?= $key ?>">
                            <img class="img-thumbnail-custom" src="<?= Utils::catchImgSrc(Utils::imgSrc($campaign->avatar_url)) ?>">
                          </a>
                      </li>
                    <?php endforeach; ?>
                  </ul>                 
          </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <h3 class="section-title title text-center">ĐỊA ĐIỂM HÀNG ĐẦU</h3>

      <div class="col-md-3">
        <div class="card"> <img class="img-small-card" src="/images/example-pitch-1.jpg" alt="">
          <div class="card-img-overlay"> <span class="badge badge-pill badge-danger">News</span> </div>
          <div class="card-body">
            <div class="news-title">
              <h2 class=" title-small"><a href="#">Syria war: Why the battle for Aleppo matters</a></h2>
            </div>
            <p class="card-text"><small class="text-time"><em>3 mins ago</em></small></p>
          </div>
        </div>

        <div class="card"> <img class="img-small-card" src="/images/example-pitch-2.jpg" alt="">
          <div class="card-img-overlay"> <span class="badge badge-pill badge-danger">Politics</span> </div>
          <div class="card-body">
            <div class="news-title">
              <h2 class=" title-small"><a href="#">Key Republicans sign letter warning against</a></h2>
            </div>
            <p class="card-text"><small class="text-time"><em>3 mins ago</em></small></p>
          </div>
        </div>

      </div>

      <div class="col-md-3">

        <div class="card"> <img class="img-small-card" src="/images/example-pitch-3.png" alt="">
          <div class="card-img-overlay"> <span class="badge badge-pill badge-danger">Travel</span> </div>
          <div class="card-body">
            <div class="news-title">
              <h2 class=" title-small"><a href="#">Obamacare Appears to Be Making People Healthier</a></h2>
            </div>
            <p class="card-text"><small class="text-time"><em>3 mins ago</em></small></p>
          </div>
        </div>

        <div class="card"> <img class="img-small-card" src="/images/example-pitch-4.jpg" alt="">
          <div class="card-img-overlay"> <span class="badge badge-pill badge-danger">News</span> </div>
          <div class="card-body">
            <div class="news-title">
              <h2 class=" title-small"><a href="#">‘S.N.L.’ to Lose Two Longtime Cast Members</a></h2>
            </div>
            <p class="card-text"><small class="text-time"><em>3 mins ago</em></small></p>
          </div>
        </div>

      </div> 

      <div class="col-md-6 top-slider">
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
          <!-- Indicators -->
          <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" 
            class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
          </ol>

          <!-- Wrapper for slides -->
          <div class="carousel-inner" role="listbox">
            <div class="item slides active">
              <div class="news-block">
                <div class="news-media"><img class="img-fluid" src="http://grafreez.com/wp-content/temp_demos/river/img/politics1.jpg" alt=""></div>
                <div class="news-title">
                  <h2 class=" title-large"><a href="#">Ray madison may struggle to get best from Paul in a 4-2-3-1 formation</a></h2>
                </div>
                <div class="news-des">Condimentum ultrices mi est a arcu at cum a elementum per cum turpis dui vulputate vestibulum in vehicula montes vel. Mauris nam suspendisse consectetur mus...</div>
                <div class="time-text"><strong>2h ago</strong></div>
                <div></div>
              </div>
            </div>
            <div class="item slides">
              <div class="news-block">
                <div class="news-media"><img class="img-fluid" src="http://grafreez.com/wp-content/temp_demos/river/img/sport1.jpg" alt=""></div>
                <div class="news-title">
                  <h2 class=" title-large"><a href="#">An Alternative Form of Mental Health Care Gains a Foothold</a></h2>
                </div>
                <div class="news-des">Condimentum ultrices mi est a arcu at cum a elementum per cum turpis dui vulputate vestibulum in vehicula montes vel. Mauris nam suspendisse consectetur mus...</div>
                <div class="time-text"><strong>2h ago</strong></div>
                <div></div>
              </div>
            </div>
            <div class="item slides">
              <div class="news-block">
                <div class="news-media"><img class="img-fluid" src="http://grafreez.com/wp-content/temp_demos/river/img/health.jpg" alt=""></div>
                <div class="news-title">
                  <h2 class=" title-large"><a href="#">Key Republican Senator Says She Will Not Vote for former president!</a></h2>
                </div>
                <div class="news-des">Condimentum ultrices mi est a arcu at cum a elementum per cum turpis dui vulputate vestibulum in vehicula montes vel. Mauris nam suspendisse consectetur mus...</div>
                <div class="time-text"><strong>2h ago</strong></div>
                <div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

