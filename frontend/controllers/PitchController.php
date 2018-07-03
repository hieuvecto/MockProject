<?php

namespace frontend\controllers;

use Yii;
use common\models\Pitch;
use common\models\PitchSearch;
use common\models\SubPitch;
use frontend\models\PitchForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * PitchController implements the CRUD actions for Pitch model.
 */
class PitchController extends Controller
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
                        'actions' => ['create'],                 
                        'roles' => ['@'],

                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'update', 'delete', 'extend'],
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
                        'actions' => ['index'],
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
    public function actionIndex()
    {
        $searchModel = new PitchSearch();
        $params = Yii::$app->request->queryParams;
        $params['PitchSearch']['owner_id'] = Yii::$app->owner->identity->owner_id;
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
     * Displays a single Pitch model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   
        $pitch = $this->findModel($id);
        $subPitches = $pitch->getSubPitches()->all();

        if (count($subPitches) > 1) 
            return $this->render('view-multiple', [
                'pitch' => $pitch,
                'subPitches' => $subPitches
            ]);

        return $this->render('view', [
            'pitch' => $pitch,
            'subPitch' => $subPitches[0],
        ]);
    }

    /**
     * Creates a new Pitch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        $pitchForm = new PitchForm;

        if ($pitchForm->Pitch->load(Yii::$app->request->post()) &&
            $pitchForm->SubPitch->load(Yii::$app->request->post()))
        {
            if ($pitchForm->save())
                return $this->redirect(['view', 'id' => $pitchForm->Pitch->pitch_id]);
        } 

        return $this->render('create', ['pitchForm' => $pitchForm]);
    }

    /**
     * Extend a sub pitch of Pitch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionExtend($id)
    {   
        $pitch = $this->findModel($id);
        $count = $pitch->getSubPitches()->count()+1;
        $model = new SubPitch();
        $model->name = "{$pitch->name} ($count)";
        $model->pitch_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        }

        return $this->render('extend', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pitch model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $pitch = $this->findModel($id);
        $subPitches = $pitch->getSubPitches()->all();
        if (count($subPitches) > 1) 
        {
            if ($pitch->load(Yii::$app->request->post()) && $pitch->save())
            {
                return $this->redirect(['view', 'id' => $pitch->pitch_id]);
            } 

            return $this->render('update-multiple', ['model' => $pitch]);
        }
        else 
        {
            $pitchForm = new PitchForm($pitch, $subPitches[0]);

            if ($pitchForm->Pitch->load(Yii::$app->request->post()) &&
                $pitchForm->SubPitch->load(Yii::$app->request->post()))
            {
                if ($pitchForm->save())
                    return $this->redirect(['view', 'id' => $pitchForm->Pitch->pitch_id]);
            } 

            return $this->render('update', ['pitchForm' => $pitchForm]);
        }
        
    }

    /**
     * Deletes an existing Pitch model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   
        SubPitch::deleteAll(['pitch_id' => $id]);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pitch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Pitch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pitch::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function isAuthor()
    {   
        return $this->findModel(Yii::$app->request->get('id'))->owner_id == Yii::$app->owner->identity->owner_id;
    }
}
