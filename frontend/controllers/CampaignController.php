<?php

namespace frontend\controllers;

use Yii;
use common\models\Campaign;
use common\models\CampaignSearch;
use common\models\SubPitch;
use common\helpers\Utils;
use frontend\models\CampaignForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\HttpException;
/**
 * SubPitchController implements the CRUD actions for SubPitch model.
 */
class CampaignController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'user'=>'owner', // this user object defined in web.php
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index'],                 
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
                        'actions' => ['view-public'],                 
                        'roles' => ['?', '@'],
                    ],
                ],
            ]
        ];
    }

     /**
     * Lists all Booking models.
     * @return mixed
     */
    public function actionIndex()
    {   
        $searchModel = new CampaignSearch();
        $params = Yii::$app->request->queryParams;
        $params['CampaignSearch']['owner_id'] = Yii::$app->owner->identity->owner_id;
        $dataProvider = $searchModel->search($params);
        $dataProvider->pagination = [
            'pageSize' => 10,
        ];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        $campaignForm = new CampaignForm();
        
        if ($campaignForm->load(Yii::$app->request->post()) &&
            $campaignForm->Campaign->load(Yii::$app->request->post()) &&
            $campaignForm->save()) 
        {
            return $this->redirect(['view', 'id' => $campaignForm->Campaign->campaign_id]);
        }

        $subPitches = Yii::$app->owner->identity->getSubPitches()->all();

        if (count($subPitches) === 0) 
            throw new HttpException(403, 'You must create at least one Pitch to perform this action.');

        return $this->render('create', [
            'campaignForm' => $campaignForm,
            'subPitches' => $subPitches,
        ]);
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
        $subPitches = $model->getSubPitches()->all();

        $sub_pitch_ids = [];
        foreach ($subPitches as $subPitch) {
            $sub_pitch_ids[] = $subPitch->sub_pitch_id;
        }

        $campaignForm = new CampaignForm($model, $sub_pitch_ids);
        
        if ($campaignForm->load(Yii::$app->request->post()) &&
            $campaignForm->Campaign->load(Yii::$app->request->post()) &&
            $campaignForm->save()) 
        {
            return $this->redirect(['view', 'id' => $campaignForm->Campaign->campaign_id]);
        }

        $subPitchesOwner = Yii::$app->owner->identity->getSubPitches()->all();

        if (count($subPitchesOwner) === 0) 
            throw new HttpException(403, 'You must create at least one Pitch to perform this action.');

        return $this->render('update', [
            'campaignForm' => $campaignForm,
            'subPitches' => $subPitchesOwner,
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
        $model = $this->findModel($id);
        
        if ($model->delete() === false)
        {
            Yii::$app->session->setFlash('error', 'There was an error deleting campaign. Try again');
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->redirect('index');
    }


    public function actionView($id)
    {
        $model = $this->findModel($id);
        $subPitches = $model->getSubPitches()->all();

        return $this->render('view', [
            'model' => $model,
            'subPitches' => $subPitches,
        ]);
    }

    public function actionViewPublic($id)
    {   
        $this->layout = 'main';
        
        $model = $this->findModel($id);
        $subPitches = $model->getSubPitches()->all();

        return $this->render('view-public', [
            'model' => $model,
            'subPitches' => $subPitches,
        ]);
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
        if (($model = Campaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function isAuthor()
    {   
        $campaign = $this->findModel(Yii::$app->request->get('id'))->getOwner()->one();
        return $campaign->owner_id == Yii::$app->owner->identity->owner_id;
    }
}
