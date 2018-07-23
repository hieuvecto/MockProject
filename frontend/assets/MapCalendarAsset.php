<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MapCalendarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libs/fullcalendar-3.9.0/fullcalendar.min.css',
        'css/site.scss',
    ];
    public $js = [
        'libs/moment-with-locales.min.js',
        'libs/fullcalendar-3.9.0/fullcalendar.min.js',
        'js/site.js',
        'js/has-fullCalendar.js',
        'js/map.js',
        'https://maps.googleapis.com/maps/api/js?callback=initMap',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
