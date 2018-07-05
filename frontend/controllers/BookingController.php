<?php

namespace frontend\controllers;

use Yii;
use common\models\Booking;
use common\models\BookingSearch;
use common\models\Pitch;
use common\models\PitchSearch;
use common\models\SubPitch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingController extends Controller
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
                        'actions' => ['create'],                 
                        'roles' => ['@'],

                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'update', 'delete'],
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
                        'actions' => ['pitches', 'dashboard', 'view-pitch'],
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Lists all Pitch models.
     * @return mixed
     */
    public function actionPitches()
    {
        $searchModel = new PitchSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = [
            'pageSize' => 10,
        ];

        return $this->render('pitches', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Booking models.
     * @return mixed
     */
    public function actionDashboard()
    {
        $searchModel = new BookingSearch();
        $params = Yii::$app->request->queryParams;
        $params['BookingSearch']['user_id'] = Yii::$app->user->identity->user_id;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination = [
            'pageSize' => 10,
        ];

        return $this->render('dashboard', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pitch model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewPitch($pitch_id)
    {
        $pitch = $this->findPitchModel($pitch_id);
        $subPitches = $pitch->getSubPitches()->all();

        if (count($subPitches) > 1) 
            return $this->render('view-pitch-multiple', [
                'pitch' => $pitch,
                'subPitches' => $subPitches
            ]);

        return $this->render('view-pitch', [
            'pitch' => $pitch,
            'subPitch' => $subPitches[0],
        ]);
    }

    /**
     * Displays a single Booking model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   
        $model = $this->findModel($id);
        $user = $model->getUser()->one();
        $subPitch = $this->findSubPitchModel($model->sub_pitch_id);
        $pitch = $subPitch->getPitch()->one();

        return $this->render('view', [
            'model' => $model,
            'user' => $user,
            'pitch' => $pitch,
            'subPitch' => $subPitch,
        ]);
    }

    /**
     * Creates a new Booking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($sub_pitch_id)
    {   
        $subPitch = $this->findSubPitchModel($sub_pitch_id);
        $model = new Booking();
        $model->sub_pitch_id = $sub_pitch_id;
        $model->user_id = Yii::$app->user->identity->user_id;

        if ($model->load(Yii::$app->request->post())) {
            $model->total_price = Booking::subtractTime($model->end_time, $model->start_time) * $subPitch->price_per_hour;
            $model->validateTime($subPitch);
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->booking_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'subPitch' => $subPitch 
        ]);
    }

    /**
     * Updates an existing Booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->is_verified) 
            throw new HttpException(403, 'This booking was verified by the pitch owner. So you can not do update or delete action.');
        $subPitch = $this->findSubPitchModel($model->sub_pitch_id);

        if ($model->load(Yii::$app->request->post())) {
            $model->total_price = Booking::subtractTime($model->end_time, $model->start_time) * $subPitch->price_per_hour;
            $model->validateTime($subPitch);
            Yii::info($model->hasErrors(), 'Has Errors');
            if ($model->save())
                return $this->redirect(['view', 'id' => $model->booking_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'subPitch' => $subPitch,
        ]);
    }

    /**
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   
        $model = $this->findModel($id);
        if ($model->is_verified) 
            throw new HttpException(403, 'This booking was verified by the pitch owner. So you can not do update or delete action.');

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function isAuthor()
    {   
        return $this->findModel(Yii::$app->request->get('id'))->user_id == Yii::$app->user->identity->user_id;
    }

    protected function findPitchModel($id)
    {
        if (($model = Pitch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findSubPitchModel($id)
    {
        if (($model = SubPitch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
