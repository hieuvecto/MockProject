<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PitchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pitches';

$this->params['keyword'] = $keyword;

$this->registerJs(
   '$("document").ready(function(){ 
        $("#pitch-filter").on("pjax:end", function() {
            $.pjax.reload({container:"#pitch-list"});  //Reload view
        });
    });'
); 
?>

<div class="container horiz-searchform box">
    <?php Pjax::begin(['id' => 'pitch-filter',
        'options' => [
            'class' => 'row',
        ]
    ]); ?>
        <?php $form = ActiveForm::begin(['id' => 'login-form',
                    'method' => 'get',
                    'options' => [
                            'class' => 'login100-form validate-form',
                            'data-pjax' => true 
                    ]]); ?>
        <div class="col-md-2">
            <div class="wrap-input validate-input">
                <span class="label-input">Tên sân</span>
                <div class="form-group">
                    <?= $form->field($searchModel, 'name')->textInput(['class' => 'input101'])->label(false) ?>
                </div>                        
            </div> 
        </div>
        
        <div class="col-md-2">
            <div class="wrap-input validate-input">
                <span class="label-input">Thành Phố</span>
                <div class="form-group">
                    <?= $form->field($searchModel, 'city')->dropdownList([
                        '' => '',
                        'Đà Nẵng' => 'Đà Nẵng'
                    ],
                    ['autofocus' => true, 'class' => 'input101'])->label(false) ?>
                </div>                        
            </div>
        </div>
        <div class="col-md-2">
            <div class="wrap-input validate-input">
                <span class="label-input">Quận/huyện</span>
                <div class="form-group">
                    <?= $form->field($searchModel, 'district')->dropdownList([
                        '' => '',
                        'Cẩm Lệ' => 'Cẩm Lệ',
                        'Hải Châu' => 'Hải Châu',
                        'Hòa Vang' => 'Hòa Vang',
                        'Liên Chiểu' => 'Liên Chiểu',
                        'Ngũ Hành Sơn' => 'Ngũ Hành Sơn',
                        'Sơn Trà' => 'Sơn Trà',
                        'Thanh Khê' => 'Thanh Khê',
                    ],
                    ['class' => 'input101'])->label(false) ?>
                </div>                        
            </div> 
        </div>
        <div class="col-md-2">
            <div class="wrap-input validate-input">
                <span class="label-input">Địa chỉ</span>
                <div class="form-group">
                    <?= $form->field($searchModel, 'address')->textInput(['class' => 'input101'])->label(false) ?>
                </div>                        
            </div> 
        </div>
        <div class="col-md-2">
            <div class="wrap-input validate-input">
                <span class="label-input">Loại sân</span>
                <div class="form-group">
                    <?= $form->field($searchModel, 'size')->dropdownList([
                        '' => '',
                        5 => '5 người',
                        7 => '7 người',
                        9 => '9 người',
                        11 => '11 người',
                    ],['class' => 'input101'])->label(false) ?>
                </div>                        
            </div> 
        </div>
        <div class="col-md-2 float-right">
            <?= Html::submitButton('Tìm sân', [
                'class' => 'btn btn-hero btn-lg'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?> 
</div>

<?php Pjax::begin(['id' => 'pitch-list',
        'options' => [
            'class' => 'container p-tb-50 list-view',
        ]
    ]); ?> 
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_list.twig',
        'options' => [
            'class' => 'row',
        ]
    ]); ?>
<?php Pjax::end(); ?>


