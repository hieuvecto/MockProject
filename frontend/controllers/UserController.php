<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\UserSearch;
use common\models\LoginForm;
use common\helpers\Utils;
use common\components\AuthUserHandler;
use common\components\AuthAction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'user', // this user object defined in web.php
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],                    
                        'roles' => ['?'],

                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'update', 'change-password'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isAuthor()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['auth'],                    
                        'roles' => ['?', '@'],

                    ],
                ],
            ]
        ];
    }

    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::className(),
                'returnUrl' => 'https://app-frontend.com/user/auth?authclient=',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {   
        $pre_state = Yii::$app->user->isGuest;
        $result = (new AuthUserHandler($client))->handle();

        if ($result)
            if ($pre_state)
                return $this->goHome();
            else
                return $this->redirect(['view', 'id' => Yii::$app->user->identity->user_id]);
        if (Yii::$app->user->isGuest)
            return $this->redirect('login');

        return $this->redirect(['view', 'id' => Yii::$app->user->identity->user_id]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   
        $model = $this->findModel($id);
        $socials = [
            'facebook' => [ 'source' => 'facebook', 'is_render' => true],
            'twitter' => [ 'source' => 'twitter', 'is_render' => true],
            'google' => [ 'source' => 'google', 'is_render' => true],
        ];

        foreach ($socials as $key => $social) {
            if ($model->getAuths([ 'source' => $social['source'] ])->exists())
                $socials[$key]['is_render'] = false;
        }

        return $this->render('view', [
            'model' => $model,
            'socials' => $socials,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSignup()
    {   
        $this->layout = 'simple';
        
        $model = new User();

        if ($model->load(Yii::$app->request->post()) ) {

            if ($model->password != $model->password_confirm) 
            {
                $flag = false;
                $model->addError('password_confirm', 'Xác nhận mật khẩu không trùng nhau.');
            }
            elseif ($model->save()) {
                if (Yii::$app->user->login($model)) 
                    return $this->redirect(['view', 'id' => $model->user_id]);
                return $this->redirect(['login']);
            }
         
        }
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Changes password of current user.
     * If change is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangePassword($id)
    {
        $model = $this->findModel($id);
        $password = $model->password;
        $flag = true;

        if ($model->load(Yii::$app->request->post())) 
        {          
            if (!Yii::$app->security->validatePassword($model->old_password, $password)) 
            {   
                $flag = false;
                $model->addError('old_password', 'Mật khẩu cũ không khớp.');
            }
            if ($model->password != $model->password_confirm) 
            {
                $flag = false;
                $model->addError('password_confirm', 'Xác nhận mật khẩu không trùng nhau.');
            }
            if ($flag) 
            {
                $model->password = Yii::$app->getSecurity()
                                ->generatePasswordHash($model->password);
                if ($model->save())
                    return $this->redirect(['view', 'id' => $model->user_id]);
            }           
        }

        $model->password = $model->old_password = $model->password_confirm = '';

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {   
        $this->layout = 'simple';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(User::className());
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function isAuthor()
    {   
        return $this->findModel(Yii::$app->request->get('id'))->user_id == Yii::$app->user->identity->user_id;
    }
}
