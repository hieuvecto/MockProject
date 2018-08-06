<?php
use yii\web\View;
use common\helpers\Utils;
use common\models\Owner;
use yii\helpers\Html;

$this->title = 'Bảng điều khiển';
$this->registerJsFile('/libs/Chart.js-2.7.2/dist/Chart.min.js', [
	'depends' => '\yii\web\JqueryAsset'
]);

$arrayWeek = json_encode(Utils::getWeekLabels());
$arrayMonth = json_encode(Utils::getMonthLabels());

$this->registerJs("
var weekLabels = $arrayWeek;
var monthLabels = $arrayMonth;
", View::POS_HEAD);

$this->registerJsFile('/js/owner-dashboard.js', [
	'depends' => '\frontend\assets\AppOwnerAsset'
]);
?>
<section class="content-header">
      <h1 style="padding-left: 15px;">
        <?= Html::encode($this->title) ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

<section class="content">
	<div class="row">
		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $countPitches ?></h3>

              <p>Số sân bóng</p>
            </div>
            <div class="icon">
              <i class="fa fa-futbol-o"></i>
            </div>
            <a href="<?= $countPitches > 0 ? '/pitch/index' : '/pitch/create' ?>" class="small-box-footer">
            	<?= $countPitches > 0 ? 'Thông tin thêm' : 'Tạo sân' ?>
            	<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
		<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $countTotal ?></h3>

              <p>Tổng đặt sân</p>
            </div>
            <div class="icon">
              <i class="fa fa-envelope-o"></i>
            </div>
            <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $countUnverified ?></h3>

              <p>Số đặt sân chưa thanh toán</p>
            </div>
            <div class="icon">
              <i class="fa fa-envelope-open-o"></i>
            </div>
            <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
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
		<div class="col-md-6">
			<div class="box box-success">
	            <div class="box-header with-border">
	              <h3 class="box-title">Doanh thu các tháng trong năm</h3>

	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	              </div>
	            </div>
	            <div class="box-body">
	              <div class="chart">
	                <canvas id="month-revenue-chart" style="height: 287px; width: 562px;" width="618" height="315"></canvas>
	              </div>
	            </div>
	            <!-- /.box-body -->
          </div>
		</div>
	</div>
</section>
