<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class OwnerMapCalendarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libs/AdminLTE-2.4.5/dist/css/AdminLTE.min.css',
        'libs/AdminLTE-2.4.5/dist/css/skins/skin-green.min.css',
        'libs/fullcalendar-3.9.0/fullcalendar.min.css',
        'css/site-owner.scss'
    ];
    public $js = [
        'libs/AdminLTE-2.4.5/dist/js/adminlte.min.js',
        'libs/moment-with-locales.min.js',
        'libs/fullcalendar-3.9.0/fullcalendar.min.js',
        'js/site-owner.js',
        'js/has-fullCalendar.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
