<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppOwnerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libs/AdminLTE-2.4.5/dist/css/AdminLTE.min.css',
        'libs/AdminLTE-2.4.5/dist/css/skins/skin-green.min.css',
        'css/site-owner.scss'
    ];
    public $js = [
        'libs/AdminLTE-2.4.5/dist/js/adminlte.min.js',
        'js/site-owner.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
