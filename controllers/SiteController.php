<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Users;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->render('index');
        }
        else{
            return $this->redirect(['login']);
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {   
        if (!\Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->role >= 5){
                $modelsmanager = Users::find()->where(['role' => 5])->select(['id', 'username'])->all();
                $arraymanager = array();
                foreach ($modelsmanager as $modelmanager) {
                    $arraymanager[$modelmanager->id] = $modelmanager->username;
                }
                $model = new SignupForm();
                if ($model->load(Yii::$app->request->post())) {

                    if ($model->id_manager == NULL) {
                        $model->id_manager = 0;
                        if (Yii::$app->user->identity->role == 5) {
                            $model->id_manager = Yii::$app->user->identity->id;
                        }
                    }
                    if ($model->role == NULL) {
                        $model->role = 1;
                    }
                    
                    if ($user = $model->signup()) {
                        Yii::$app->session->setFlash('info', 'Пользователь создан.');
                        return $this->goHome();
                       
                    }
                }
                return $this->render('signup', [
                    'model' => $model,
                    'arraymanager' => $arraymanager,
                  
                ]);                
            }
            else{
                Yii::$app->session->setFlash('error', 'У вас не прав доступа к этому разделу сайта.');
                return $this->redirect(['index']);
            }
            
        }
        else{
            return $this->redirect(['login']);
        }
        

        
       
    }

    public function actionProfile($id)
    {   
        if (!\Yii::$app->user->isGuest) {
            $modeluser = $this->findModeluser($id);
            if(Yii::$app->user->identity->role == 10 || Yii::$app->user->identity->id == $id){
                $modelsmanager = Users::find()->where(['role' => 5])->select(['id', 'username'])->all();
                $arraymanager = array();
                foreach ($modelsmanager as $modelmanager) {
                    $arraymanager[$modelmanager->id] = $modelmanager->username;
                }
                
                if ($modeluser->load(Yii::$app->request->post())) {
                    //echo $modeluser->id_manager;
                    //die();
                    if ($modeluser->id_manager == NULL) {
                        $modeluser->id_manager = 0;
                       
                    }
                    if ($modeluser->role == NULL) {
                        $modeluser->role = 1;
                    }
                    
                    if ($modeluser->save()) {
                        Yii::$app->session->setFlash('info', 'Данные сохранены.');
                        return $this->refresh();
                       
                    }
                }
                return $this->render('profile', [
                    'model' => $modeluser,
                    'arraymanager' => $arraymanager,
                    
                  
                ]);                
            }
            else{
                Yii::$app->session->setFlash('error', 'У вас не прав доступа к этому разделу сайта.');
                return $this->redirect(['index']);
            }
            
        }
        else{
            return $this->redirect(['login']);
        }
        

        
       
    }

    public function actionPassword($id)
    {   
        if (!\Yii::$app->user->isGuest) {
            $modeluser = $this->findModeluser($id);
            if(Yii::$app->user->identity->role == 10 || Yii::$app->user->identity->id == $id){
                
                if ($modeluser->load(Yii::$app->request->post())) {
                    //echo $modeluser->id_manager;
                    //die();
                    if ($modeluser->id_manager == NULL) {
                        $modeluser->id_manager = 0;
                       
                    }
                    if ($modeluser->role == NULL) {
                        $modeluser->role = 1;
                    }
                    
                    if ($modeluser->save()) {
                        Yii::$app->session->setFlash('info', 'Данные сохранены.');
                        return $this->refresh();
                       
                    }
                }
                return $this->render('password', [
                    'model' => $modeluser,
                    
                    
                  
                ]);                
            }
            else{
                Yii::$app->session->setFlash('error', 'У вас не прав доступа к этому разделу сайта.');
                return $this->redirect(['index']);
            }
            
        }
        else{
            return $this->redirect(['login']);
        }
        

        
       
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['login']);
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

      /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Monitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModeluser($id)
    {
        if (($modeluser = Users::findOne($id)) !== null) {
            return $modeluser;
        } else {
            
            throw new NotFoundHttpException('Пользователь не найден.');
        }
    }

   
}
