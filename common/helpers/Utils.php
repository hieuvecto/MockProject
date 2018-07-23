<?php

namespace common\helpers;

use yii\helpers\Html;
use Yii;

class Utils
{
	static public function getTimeArray() {
		return [
			'00:00:00' => '00:00',
			'00:30:00' => '00:30',
			'01:00:00' => '01:00',
			'01:30:00' => '01:30',
			'02:00:00' => '02:00',
			'02:30:00' => '02:30',
			'03:00:00' => '03:00',
			'03:30:00' => '03:30',
			'04:00:00' => '04:00',
			'04:30:00' => '04:30',
			'05:00:00' => '05:00',
			'05:30:00' => '05:30',
			'06:00:00' => '06:00',
			'06:30:00' => '06:30',
			'07:00:00' => '07:00',
			'07:30:00' => '07:30',
			'08:00:00' => '08:00',
			'08:30:00' => '08:30',
			'09:00:00' => '09:00',
			'09:30:00' => '09:30',
			'10:00:00' => '10:00',
			'10:30:00' => '10:30',
			'11:00:00' => '11:00',
			'11:30:00' => '11:30',
			'12:00:00' => '12:00',
			'12:30:00' => '12:30',
			'13:00:00' => '13:00',
			'13:30:00' => '13:30',
			'14:00:00' => '14:00',
			'14:30:00' => '14:30',
			'15:00:00' => '15:00',
			'15:30:00' => '15:30',
			'16:00:00' => '16:00',
			'16:30:00' => '16:30',
			'17:00:00' => '17:00',
			'17:30:00' => '17:30',
			'18:00:00' => '18:00',
			'18:30:00' => '18:30',
			'19:00:00' => '19:00',
			'19:30:00' => '19:30',
			'20:00:00' => '20:00',
			'20:30:00' => '20:30',
			'21:00:00' => '21:00',
			'21:30:00' => '21:30',
			'22:00:00' => '22:00',
			'22:30:00' => '22:30',
			'23:00:00' => '23:00',
			'23:30:00' => '23:30',
		];
	}

	static public function imgSrc($avatar_url) 
	{
		return '/' . $avatar_url;
	}

	static public function routeLink($text, $url = null, $options = [], $optionsActive = []) 
	{
		if (Yii::$app->controller->getRoute() === $url)
			foreach ($options as $key => $value) {
				$value = $value . ' ' . $optionsActive[$key];
			}

		Yii::info($options, 'Debug options');

		return Html::a($text, $url, $options);
	}

	static public function isRoute($url) 
	{	
		if (Yii::$app->controller->getRoute() === $url)
			return 'active';
		return '';
	}

	static public function isController($controller_id) 
	{	
		if (Yii::$app->controller->id === $controller_id)
			return 'active';
		return '';
	}

	static public function catchImg($src = '', $options = [])
	{
		if (trim($src) === '' || trim($src) === '/')
			$src = '/images/no-image.svg';
		return Html::img($src, $options);
	} 

	static public function catchImgSrc($src = '')
	{
		if (trim($src) === '' || trim($src) === '/')
			$src = '/images/no-image.svg';
		return $src;
	}

	static public function catchIdentitySrc($src = '')
	{
		if (trim($src) === '' || trim($src) === '/')
			$src = '/images/gravatar.png';
		return $src;
	}

	static public function catchIdentityImg($src = '', $options = [])
	{
		if (trim($src) === '' || trim($src) === '/')
			$src = '/images/gravatar.png';
		return Html::img($src, $options);
	}
}
