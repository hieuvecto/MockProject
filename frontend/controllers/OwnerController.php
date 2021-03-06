<?php

namespace frontend\controllers;

use Yii;
use common\models\Owner;
use common\models\OwnerSearch;
use common\models\LoginForm;
use common\components\AuthOwnerHandler;
use common\components\AuthAction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * OwnerController implements the CRUD actions for Owner model.
 */
class OwnerController extends Controller
{   
    public $layout = 'owner';
    
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
                    //'logout' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'owner', // this user object defined in web.php
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
                        'actions' => ['logout', 'dashboard', 'week-revenue', 'month-revenue'],
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
                'returnUrl' => 'https://app-frontend.com/owner/auth?authclient=',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        $pre_state = Yii::$app->owner->isGuest;
        $result = (new AuthOwnerHandler($client))->handle();

        if ($result)
            if ($pre_state)
                return $this->redirect('dashboard');
            else
                return $this->redirect(['view', 'id' => Yii::$app->owner->identity->owner_id]);
        if (Yii::$app->owner->isGuest)
            return $this->redirect('login');

        return $this->redirect(['view', 'id' => Yii::$app->owner->identity->owner_id]);
    }

    /**
     * Displays a single Owner model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDashboard()
    {   
        $owner = Yii::$app->owner->identity;
        $pitches = $owner->getPitches()->all();

        $count = 0;
        $countUnverfied = 0;
        foreach ($pitches as $pitch) {
            $subPitches = $pitch->getSubPitches()->all();

            foreach ($subPitches as $subPitch) 
            {   
                // count unverified bookings
                $count += $subPitch->getBookings()->count();
                $countUnverfied += $subPitch->getBookings(['is_verified' => 0])->count();
            }
        }

        return $this->render('dashboard', [
            'countPitches' => count($pitches),
            'countTotal' => $count,
            'countUnverified' => $countUnverfied,
        ]);
    }

    /**
     * Displays a single Owner model.
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
     * Creates a new Owner model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSignup()
    {   
        $this->layout = 'simple';
        $model = new Owner();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->owner->login($model)) 
                return $this->redirect(['view', 'id' => $model->owner_id]);
            
            return $this->redirect(['login']);
        }
        
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Owner model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->owner_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Changes password of current owner.
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
                $model->addError('old_password', 'Old password does not match.');
            }
            if ($model->password != $model->password_confirm) 
            {
                $flag = false;
                $model->addError('password_confirm', 'Password confirm does not match.');
            }
            if ($flag) 
            {
                $model->password = Yii::$app->getSecurity()
                                ->generatePasswordHash($model->password);
                if ($model->save())
                    return $this->redirect(['view', 'id' => $model->owner_id]);
            }           
        }

        $model->password = $model->old_password = $model->password_confirm = '';

        return $this->render('changePassword', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Owner model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Logs in a owner.
     *
     * @return mixed
     */
    public function actionLogin()
    {   
        $this->layout = 'simple';
        if (!Yii::$app->owner->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm(Owner::className());
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect('dashboard');
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
        Yii::$app->owner->logout();

        return $this->goHome();
    }

    public function actionWeekRevenue()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return Owner::weekRevenue();
    }

    public function actionMonthRevenue()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return Owner::monthRevenue();
    }
    /**
     * Finds the Owner model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Owner the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Owner::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    protected function isAuthor()
    {   
        return $this->findModel(Yii::$app->request->get('id'))->owner_id == Yii::$app->owner->identity->owner_id;
    }
}
