<?php

namespace common\helpers;

use common\helpers\Utils;
use yii\helpers\Html;
use Yii;
use yii\web\NotFoundHttpException;

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

	static public function isController($controller_id, $exceptUrl = '') 
	{	
		if (Yii::$app->controller->id === $controller_id &&
			Yii::$app->controller->getRoute() !== $exceptUrl)
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

	static public function arrrayToStrError($array)
	{	
		$error = '';
		foreach ($array as $key => $value) {
			foreach ($value as $keyVal => $valueVal) {
				if (strpos($error, $valueVal) === false)
					$error = $error  . $valueVal. ' ';
			}
		}
		return $error;
	}

	static public function getWeekLabels($format = 'd/m', $has_day = true)
	{
		$days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
		$daysVN = ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'];
		$result = [];

		foreach ($days as $key => $value) {
			if ($has_day)
				$result[] = $daysVN[$key] . ' ' . date( $format, strtotime( $value .' this week' ));
			else 
				$result[] = date( $format, strtotime( $value .' this week' ));
		}

		return $result;
	}

	static public function getMonthLabels($format = 'Y')
	{
		$months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
		$this_year = date($format);

		$result = [];

		foreach ($months as $value) {
			$result[] = $this_year . '-' . $value;
		}

		return $result;
	}

	static public function getFlashes()
	{
		$session = Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $returnMesaage = '';
        foreach ($flashes as $type => $flash) {
            foreach ((array) $flash as $i => $message) {
                $returnMesaage = $returnMesaage . $message . ' ';
            }
            $session->removeFlash($type);
        }

        return $returnMesaage;
	}

	static public function except(array $array, array $elements) 
	{	
		Yii::info($array, "Debug array");
		foreach ($elements as $element) {
			$key = array_search($element, $array);
			if ($key !== false) {
			    unset($array[$key]);
			}
			else 
				throw new NotFoundHttpException("element $element not found in array.");
		}

		return $array;
	}

	static public function has_dupes(array $array) {
	    $dupe_array = [];
	    foreach ($array as $val) {
	    	$key = 'key' . $val;

	    	if (isset($dupe_array[$key]))
	    		$dupe_array[$key] += 1;
	    	else
	    		$dupe_array[$key] = 1;
	    	
	        if ($dupe_array[$key] > 1) {
	            return true;
	        }
	    }
	    return false;
	}
}
