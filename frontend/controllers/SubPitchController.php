<?php

namespace frontend\controllers;

use Yii;
use common\models\SubPitch;
use common\models\SubPitchSearch;
use common\models\Booking;
use common\models\BookingSearch;
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
                    'get-events' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'owner', // this user object defined in web.php
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete', 'list-booking'],
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
                        'actions' => ['view-booking', 'verify'],
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
        $this->layout = 'owner';
        
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
    public function actionListBooking($id, $is_verified = 0)
    {   
        $subPitch = $this->findModel($id);
        $searchModel = new BookingSearch();
        $params = Yii::$app->request->queryParams;
        $params['BookingSearch']['sub_pitch_id'] = $id;
        $params['BookingSearch']['is_verified'] = $is_verified;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination = [
            'pageSize' => 10,
        ];

        return $this->render('list-booking', [
            'is_verified' => $is_verified,
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
        $model = $this->findBookingModel($booking_id);

        $user = $model->getUser()->one();
        $subPitch = $this->findModel($model->sub_pitch_id);
        $pitch = $subPitch->getPitch()->one();

        return $this->render('view-booking', [
            'is_verified' => $model->is_verified,
            'model' => $model,
            'user' => $user,
            'pitch' => $pitch,
            'subPitch' => $subPitch,
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
        if (!$model->save())
            Yii::$app->session->setFlash('error', 
                'There was an error verifying this booking.');
        else
            Yii::$app->session->setFlash('success', 
                'Verify successfully.');
        Yii::info($model->getErrors(), 'Get Errors');
        return $this->redirect(['view-booking', 'booking_id' => $booking_id]); 
    }

    public function actionGetEvents($id)
    {   
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $subPitch = $this->findModel($id);
        return $subPitch->getEvents();
    }

    public function actionView($id)
    {
        $pitch = $this->findModel($id)->getPitch()->one();

        $this->redirect(['booking/view-pitch', 'pitch_id' => $pitch->pitch_id]);
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