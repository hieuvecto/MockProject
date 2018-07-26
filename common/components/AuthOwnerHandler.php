<?php
namespace common\components;

use common\models\AuthOwner;
use common\models\Owner;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\authclient\clients\Google;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthOwnerHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();

        if ($this->client instanceof Google) {
            $email = ArrayHelper::getValue($attributes, 'emails')[0]['value'];
            $id = ArrayHelper::getValue($attributes, 'id');
        }
        
        /* @var Auth $auth */
        $auth = AuthOwner::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->owner->isGuest) {
            if ($auth) { // login
                /* @var Owner $owner */
                $owner = $auth->getOwner()->one();
                return 
                    Yii::$app->owner->login($owner);
            } else { // signup
                if ($email !== null && Owner::find()->where(['email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "Owner with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                    ]);
                    return false;
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $owner = new Owner([
                        'email' => $email,
                        'phone' => 'not set',
                        'password' => $password,
                    ]);

                    $transaction = Owner::getDb()->beginTransaction();

                    if ($owner->save()) {
                        $auth = new AuthOwner([
                            'owner_id' => $owner->owner_id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            return 
                                Yii::$app->owner->login($owner);
                        } else {
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($auth->getErrors()),
                                ]),
                            ]);
                            return false;
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save owner: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($owner->getErrors()),
                            ]),
                        ]);
                        return false;
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                if ($email !== Yii::$app->owner->identity->email)
                {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Mismatch email.'),
                    ]);
                    return false;
                }

                $auth = new AuthOwner([
                    'owner_id' => Yii::$app->owner->identity->owner_id,
                    'source' => $this->client->getId(),
                    'source_id' => $id,
                ]);
                if ($auth->save()) {
                    /** @var Owner $user */
                    $owner = $auth->getOwner()->one();
            
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                    return true;
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                    return false;
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another owner using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
                    return false;
            }
        }
    }
}