<?php
use yii\helpers\Html;
use yii\web\View;
use common\helpers\Utils;
use common\models\Owner;
use yii\helpers\Url;

$this->title = 'Thống kê' . ' ' . $subPitch->name;
$this->registerJsFile('/libs/Chart.js-2.7.2/dist/Chart.min.js', [
	'depends' => '\yii\web\JqueryAsset'
]);

$arrayWeek = json_encode(Utils::getWeekLabels());
$arrayMonth = json_encode(Utils::getMonthLabels());

$this->registerJs("
var weekLabels = $arrayWeek;
var monthLabels = $arrayMonth;
var sub_pitch_id = $subPitch->sub_pitch_id;
", View::POS_HEAD);

$this->registerJsFile('/js/sub-pitch-statistic.js', [
	'depends' => '\frontend\assets\AppOwnerAsset'
]);
?>
<section class="content-header" style="padding-left: 15px;">
      <h1 class="title">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-futbol-o"></i> Quản lý sân</a></li>
        <li><?= Html::a('Danh sách', ['pitch/index', 'sort' => '-created_at']) ?></a></li>
        <li><?= Html::a('Chi tiết sân', ['pitch/view', 'id' => $subPitch->pitch_id]) ?></li>
        <li class="active">Thống kê</li>
      </ol>
    </section>

<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $bookings_total ?></h3>

              <p>Tổng đặt sân</p>
            </div>
            <div class="icon">
              <i class="fa fa-envelope-o"></i>
            </div>
            <a href="<?= Url::to(['/sub-pitch/list-booking', 'id' => $subPitch->sub_pitch_id, 
              'sort' => '-created_at']) ?>" 
                    class="small-box-footer">Thông tin thêm <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $unverified_bookings ?></h3>

              <p>Số đặt sân chưa xác nhận</p>
            </div>
            <div class="icon">
              <i class="fa fa-envelope-open-o"></i>
            </div>
            <a href="<?= Url::to(['/sub-pitch/list-booking', 'id' => $subPitch->sub_pitch_id, 'BookingSearch' => [ 'is_verified' => 0], 'sort' => '-created_at']) ?>" 
            class="small-box-footer">Thông tin thêm <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Doanh thu trong tuần</h3>

	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	              </div>
	            </div>
	            <div class="box-body">
	              <div class="chart">
	                <canvas id="week-revenue-chart" style="height: 287px; width: 562px;" width="618" height="315"></canvas>
	              </div>
	            </div>
	            <!-- /.box-body -->
          </div>
		</div>
	</div>
</section>
