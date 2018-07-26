<?php
namespace common\components;

use common\models\AuthUser;
use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\authclient\clients\Google;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthUserHandler
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
        $auth = AuthUser::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var User $user */
                $user = $auth->getUser()->one();
                return 
                    Yii::$app->user->login($user);
            } else { // signup
                if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', "User with the same email as in {client} account already exists but isn't linked to it. Login using email first to link it.", ['client' => $this->client->getTitle()]),
                    ]);
                    return false;
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'email' => $email,
                        'phone' => 'not set',
                        'password' => $password,
                    ]);

                    $transaction = User::getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new AuthUser([
                            'user_id' => $user->user_id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            return 
                                Yii::$app->user->login($user);
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
                            Yii::t('app', 'Unable to save user: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($user->getErrors()),
                            ]),
                        ]);
                        return false;
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                if ($email !== Yii::$app->user->identity->email)
                {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Mismatch email.'),
                    ]);
                    return false;
                }

                $auth = new AuthUser([
                    'user_id' => Yii::$app->user->identity->user_id,
                    'source' => $this->client->getId(),
                    'source_id' => $id,
                ]);
                if ($auth->save()) {
                    /** @var User $user */
                    $user = $auth->getUser()->one();
            
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
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
                    return false;
            }
        }
    }
}