<?php

namespace frontend\controllers;

use Yii;
use common\models\SubPitch;
use common\models\SubPitchSearch;
use common\models\Booking;
use common\models\BookingSearch;
use common\helpers\Utils;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
/**
 * SubPitchController implements the CRUD actions for SubPitch model.
 */
class SubPitchController extends Controller
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
                    'verify' => ['POST'],
                    'pay' => ['POST'],
                    'get-events' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'owner', // this user object defined in web.php
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete', 'list-booking', 'statistic', 'week-revenue',  'create-booking'],
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
                        'actions' => ['view-booking', 'verify', 'pay'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isOwnerOfPitch()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['get-events', 'view'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ]
        ];
    }

    /**
     * Updates an existing SubPitch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {   
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            return $this->redirect(['pitch/view', 'id' => $model->pitch_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SubPitch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   
        $subPitch = $this->findModel($id);
        $pitch_id = $subPitch->pitch_id;
        $count = SubPitch::find()
            ->where(['pitch_id' => $pitch_id])
            ->count();

        if ($count > 1)
            $subPitch->delete();
        else 
            throw new HttpException(403, 'You\'re not allowed to do this action.');
            
        return $this->redirect(['pitch/view', 'id' => $pitch_id]);
    }

    /**
     * Lists all Booking models.
     * @return mixed
     */
    public function actionListBooking($id)
    {   
        $subPitch = $this->findModel($id);
        $searchModel = new BookingSearch();
        $params = Yii::$app->request->queryParams;
        $params['BookingSearch']['sub_pitch_id'] = $id;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination = [
            'pageSize' => 10,
        ];

        return $this->render('list-booking', [
            'subPitch' => $subPitch,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Views an booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewBooking($booking_id)
    {   
        $this->layout = 'owner-map-calendar';

        $model = $this->findBookingModel($booking_id);

        $user = $model->getUser()->one();
        $subPitch = $this->findModel($model->sub_pitch_id);
        $pitch = $subPitch->getPitch()->one();
        $count = $pitch->getSubPitches()->count();

        return $this->render('view-booking', [
            'model' => $model,
            'user' => $user,
            'pitch' => $pitch,
            'subPitch' => $subPitch,
        ]);
    }

    public function actionCreateBooking($id)
    {   
        $subPitch = $this->findModel($id);
        $pitch = $subPitch->getPitch()->one();
        $model = new Booking();
        $model->sub_pitch_id = $subPitch->sub_pitch_id;
        $model->user_id = 99;
        $model->is_verified = '1';
        $model->is_paid = '1';
        $model->is_validateBookDay = false;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save())
                return $this->redirect(['view-booking', 'booking_id' => $model->booking_id]);
        }

        Yii::info($model->getErrors(), 'GEt error');
        return $this->render('create-booking', [
            'model' => $model,
            'subPitch' => $subPitch,
            'pitch' => $pitch,
        ]);
    }

    /**
     * Verifies an unverified booking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionVerify($booking_id)
    {
        $model = $this->findBookingModel($booking_id);

        if ($model->is_verified)
            throw new HttpException(403, 'This booking was verifed.');

        $model->is_verified = '1';
        $model->is_validateBookDay = false;
        if (!$model->save())
        {   
            Yii::info($model->getErrors(), 'Get Errors');
            Yii::$app->session->setFlash('error', 
                'Đã có lỗi khi xác nhận đặt sân này: ' . 
                Utils::arrrayToStrError($model->getErrors()) );
        }
        else
            Yii::$app->session->setFlash('success', 
                'Xác nhận đặt sân thành công.');

        return $this->redirect(['view-booking', 'booking_id' => $booking_id]); 
    }

    public function actionPay($booking_id)
    {
        $model = $this->findBookingModel($booking_id);

        if (!$model->is_verified)
            throw new HttpException(403, 'This booking was not verifed.');

        if ($model->is_paid)
            throw new HttpException(403, 'This booking was paid.');

        $model->is_paid = '1';
        $model->is_validateBookDay = false;
        
        if (!$model->save())
        {   
            Yii::info($model->getErrors(), 'Get Errors');
            Yii::$app->session->setFlash('error', 
                'Đã có lỗi khi Thanh toán đặt sân này: ' . 
                Utils::arrrayToStrError($model->getErrors()) );
        }
        else
            Yii::$app->session->setFlash('success', 
                'Thanh toán đặt sân thành công.');

        return $this->redirect(['view-booking', 'booking_id' => $booking_id]); 
    }


    public function actionGetEvents($id)
    {   
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $subPitch = $this->findModel($id);
        return $subPitch->getEvents();
    }

    public function actionView($id, $page = 'user')
    {
        $pitch = $this->findModel($id)->getPitch()->one();
        if ($page === 'user')
            return $this->redirect(['booking/view-pitch', 'pitch_id' => $pitch->pitch_id]);
        if ($page === 'owner')
            return $this->redirect(['pitch/view', 'id' => $pitch->pitch_id]);

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionStatistic($id)
    {   
        $subPitch = $this->findModel($id);
        $bookings_total = $subPitch->getBookings()->count();
        $unverified_bookings = $subPitch->getBookings(['is_verified' => 0])->count();

        return $this->render('statistic', [
            'subPitch' => $subPitch,
            'bookings_total' => $bookings_total,
            'unverified_bookings' => $unverified_bookings,
        ]);
    }

    public function actionWeekRevenue($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return SubPitch::weekRevenue($id);
    }
    /**
     * Finds the SubPitch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SubPitch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SubPitch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findBookingModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function isAuthor()
    {   
        $pitch = $this->findModel(Yii::$app->request->get('id'))->getPitch()->one();
        return $pitch->owner_id == Yii::$app->owner->identity->owner_id;
    }

    protected function isOwnerOfPitch()
    {   
        $pitch = $this->findBookingModel(Yii::$app->request->get('booking_id'))
            ->getSubPitch()->one()->getPitch()->one();
        return $pitch->owner_id == Yii::$app->owner->identity->owner_id;
    }
}
