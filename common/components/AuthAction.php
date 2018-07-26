<?php
namespace common\components;

use Yii;
use yii\base\NotSupportedException;
use yii\helpers\Url;
use yii\authclient\OAuth1;
use yii\authclient\OAuth2;
use yii\authclient\OpenId;
/**
* 
*/
class AuthAction extends \yii\authclient\AuthAction
{	
	public $returnUrl;
	/**
     * Perform authentication for the given client.
     * @param mixed $client auth client instance.
     * @return Response response instance.
     * @throws \yii\base\NotSupportedException on invalid client.
     */
    protected function auth($client)
    {   
    	Yii::info(Yii::$app->request->getQueryParam('authclient'));
        $client->setReturnUrl($this->returnUrl . Yii::$app->request->getQueryParam('authclient'));
        if ($client instanceof OAuth2) {
            return $this->authOAuth2($client);
        } elseif ($client instanceof OAuth1) {
            return $this->authOAuth1($client);
        } elseif ($client instanceof OpenId) {
            return $this->authOpenId($client);
        }

        throw new NotSupportedException('Provider "' . get_class($client) . '" is not supported.');
    }
}
