<?php

namespace frontend\controllers;

use Yii;
use common\models\SubPitch;
use common\models\SubPitchSearch;
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'owner', // this user object defined in web.php
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'delete'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if ($this->isAuthor()) {
                                return true;
                            }
                            return false;
                        }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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

    protected function isAuthor()
    {   
        $pitch = $this->findModel(Yii::$app->request->get('id'))->getPitch()->one();
        return $pitch->owner_id == Yii::$app->owner->identity->owner_id;
    }
}
