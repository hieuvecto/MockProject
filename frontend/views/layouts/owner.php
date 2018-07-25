<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppOwnerAsset;
use common\widgets\Alert;

AppOwnerAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $this->head() ?>
</head>
<body class="skin-green sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
	<?= $this->render('_owner-header') ?>
	<?= $this->render('_owner-sidebar') ?>
	<div class="content-wrapper">
		<?= Alert::widget() ?>
		<?= $content ?>
	</div>
	<?= $this->render('_owner-footer') ?>
	<?= $this->render('_owner-control-sidebar') ?>
	<div class="control-sidebar-bg"></div>
</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
