<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\data\Pagination;
use yii\data\Sort;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\Users;
use app\models\User;
use app\models\Password;
use app\models\Object;
use app\models\Router;
use app\models\Post;


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
                        return $this->redirect(['users']);
                       
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
            $modeluser = $this->findModelprofile($id);
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
            if(Yii::$app->user->identity->id == $id){
                
                $modelpassword = new Password();
                $modelpassword->username = $modeluser->username;
                if ($modelpassword->load(Yii::$app->request->post())) {
                    if ($modelpassword->validate()) {
                        $modeluser->setPassword($modelpassword->new_password);
                        if ($modeluser->save()) {
                            Yii::$app->session->setFlash('info', 'Новый парль сохранен.');
                            return $this->refresh();
                        }
                        else{
                            Yii::$app->session->setFlash('error', 'Новый парль не сохранен.');
                            return $this->refresh();
                        }
                    }
                   
                    
                   
                }
                return $this->render('password', [
                    'model' => $modelpassword,
                    
                    
                  
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

    public function actionUsers(){
        if (!\Yii::$app->user->isGuest) {
            if(Yii::$app->user->identity->role >= 5){
                $sort = new Sort([
                    'attributes' => [
                        'id' =>[
                            'label' => 'ID',
                        ],
                        'username' =>[
                            'asc' => ['username' => SORT_ASC],
                            'desc' => ['username' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'Имя пользователя',
                        ],
                        'role' =>[
                            'label' => 'Тип аккаунта',
                        ],
                        'id_manager' =>[
                            'label' => 'Менеджер',
                        ],
                        'created_at' =>[
                            'label' => 'Дата регистрации',
                        ],
                        'status' =>[
                            'label' => 'Статус',
                        ],
                        
                        
                    ],
                ]);

                if (Yii::$app->user->identity->role == 5) {
                    $query = Users::find()->where(['id_manager' => Yii::$app->user->identity->id, 'status' => 10])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 10) {
                    $query = Users::find()->orderBy($sort->orders);
                }
                $countQuery = clone $query;
                $pages = new Pagination(['totalCount' => $countQuery->count()]);
                $modelsuser = $query->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();

                //get array manager
                $modelsmanager = Users::find()->where(['role' => 5])->select(['id', 'username'])->all();
                $arraymanager = array();
                foreach ($modelsmanager as $modelmanager) {
                    $arraymanager[$modelmanager->id] = $modelmanager->username;
                }
                //get array manager

                    return $this->render('users', [
                        'modelsuser' => $modelsuser,
                        'arraymanager' => $arraymanager,
                        'pages' => $pages,
                        'sort' => $sort,
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

     /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteuser($id)
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 10) {
                $this->findModelprofile($id)->delete();
                Yii::$app->session->setFlash('info', 'Пользователь удален.');
                return $this->redirect(['users']);
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

    //CRUD object

    /**
     * Creates a new Object model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateobject()
    {
        if (!\Yii::$app->user->isGuest) {
            $modelobject = new Object();
            $arrayuser = array();
            if (Yii::$app->user->identity->role == 10) {
                $modelsuser = Users::find()->where(['role' => 1])->select(['id', 'username'])->all();

            }
            if (Yii::$app->user->identity->role == 5) {
                $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id', 'username'])->all();
            }
            if ($modelsuser != NULL) {
                foreach ($modelsuser as $modeluser) {
                    $arrayuser[$modeluser->id] = $modeluser->username;
                }
            }
            
            //default user id
            $modelobject->id_user = Yii::$app->user->identity->id;
            //default user id
            if ($modelobject->load(Yii::$app->request->post())) {
                //$modelobject->id_user = Yii::$app->user->identity->id;
                if ($modelobject->save()) {
                    Yii::$app->session->setFlash('info', 'Заведение создано.');
                    return $this->redirect(['object', 'id' => $modelobject->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Заведение не создано.');
                    return $this->refresh();
                }
                
            } else {
                return $this->render('createobject', [
                    'model' => $modelobject,
                    'arrayuser' => $arrayuser,
                ]);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }

    /**
     * Creates a new Object model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionObject($id)
    {
        if (!\Yii::$app->user->isGuest) {
            $modelobject = $this->findModelobject($id);
            $arrayuser = array();
            if (Yii::$app->user->identity->role == 10) {
                $modelsuser = Users::find()->where(['role' => 1])->select(['id', 'username'])->all();

            }
            if (Yii::$app->user->identity->role == 5) {
                $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id', 'username'])->all();
            }
            if ($modelsuser != NULL) {
                foreach ($modelsuser as $modeluser) {
                    $arrayuser[$modeluser->id] = $modeluser->username;
                }
            }
            
            
            if ($modelobject->load(Yii::$app->request->post())) {
            
                if ($modelobject->save()) {
                    Yii::$app->session->setFlash('info', 'Заведение сохранено.');
                    return $this->redirect(['object', 'id' => $modelobject->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Заведение не сохранено.');
                    return $this->refresh();
                }
                
            } else {
                return $this->render('editobject', [
                    'model' => $modelobject,
                    'arrayuser' => $arrayuser,
                ]);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }

    public function actionObjects(){
        if (!\Yii::$app->user->isGuest) {
            $sort = new Sort([
                    'attributes' => [
                        'id' =>[
                            'label' => 'ID',
                        ],
                        'title' =>[
                            'asc' => ['title' => SORT_ASC],
                            'desc' => ['title' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'Название',
                        ],
                        'id_user' =>[
                            'asc' => ['id_user' => SORT_ASC],
                            'desc' => ['id_user' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'ID пользователся',
                        ],
                        
                        'created_at' =>[
                            'label' => 'Дата регистрации',
                        ],
                        'status' =>[
                            'label' => 'Статус',
                        ],
                        
                        
                    ],
                ]);
                if (Yii::$app->user->identity->role == 1) {
                    $query = Object::find()->where(['id_user' => Yii::$app->user->identity->id])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 5) {
                    $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                    
                    if ($modelsuser != NULL) {
                        $n_user = 0;
                        $arrayuser = array();
                        foreach ($modelsuser as $modeluser) {
                            $arrayuser[$n_user] = $modeluser->id;
                            $n_user++;
                        }
                    }
                    $query = Object::find()->where(['id_user' => $arrayuser])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 10) {
                    $query = Object::find()->orderBy($sort->orders);
                }
                $countQuery = clone $query;
                $pages = new Pagination(['totalCount' => $countQuery->count()]);
                $modelsobject = $query->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();

                

                    return $this->render('objects', [
                        'modelsobject' => $modelsobject,
                        
                        'pages' => $pages,
                        'sort' => $sort,
                    ]);

        }
        else{
            return $this->redirect(['login']);
        }
    }

    /**
     * Deletes an existing Object model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteobject($id)
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 10) {
                $this->findModelobject($id)->delete();
                Yii::$app->session->setFlash('info', 'Заведение удалено.');
                return $this->redirect(['objects']);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }

     /**
     * Finds the Object model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Monitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelobject($id)
    {
        if (($modelobject = Object::findOne($id)) !== null) {
            return $modelobject;
        } else {
            
            throw new NotFoundHttpException('Заведение не найдено.');
        }
    }


    //CRUD object

    //CRUD router
    /**
     * Creates a new Router model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreaterouter()
    {
        if (!\Yii::$app->user->isGuest) {
            $modelrouter = new Router();
            $arrayobject = array();
            if (Yii::$app->user->identity->role == 10) {
                $modelsobject = Object::find()->select(['id', 'title'])->all();

            }
            if (Yii::$app->user->identity->role == 5) {
                $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                if ($modelsuser != NULL) {
                    $n_user = 0;
                    $arrayuser = array();
                    foreach ($modelsuser as $modeluser) {
                        $arrayuser[$n_user] = $modeluser->id;
                        $n_user++;
                    }
                }

                $modelsobject = Object::find()->where(['id_user' => $arrayuser])->select(['id', 'title'])->all();
            }
            if (Yii::$app->user->identity->role == 1) {
                $modelsobject = Object::find()->where(['id_user' =>  Yii::$app->user->identity->id])->select(['id', 'title'])->all();

            }
            if ($modelsobject != NULL) {
                foreach ($modelsobject as $modelobject) {
                    $arrayobject[$modelobject->id] = $modelobject->title;
                }
            }
            
            
            if ($modelrouter->load(Yii::$app->request->post())) {
                
                if ($modelrouter->save()) {
                    Yii::$app->session->setFlash('info', 'Роутер добавлен.');
                    return $this->redirect(['router', 'id' => $modelrouter->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Заведение не создано.');
                    return $this->refresh();
                }
                
            } else {
                return $this->render('createrouter', [
                    'model' => $modelrouter,
                    'arrayobject' => $arrayobject,
                ]);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }

    /**
     * Creates a new Router model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionRouter($id)
    {
        if (!\Yii::$app->user->isGuest) {
            $modelrouter = $this->findModelrouter($id);
            $arrayobject = array();
            if (Yii::$app->user->identity->role == 10) {
                $modelsobject = Object::find()->select(['id', 'title'])->all();

            }
            if (Yii::$app->user->identity->role == 5) {
                $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                if ($modelsuser != NULL) {
                    $n_user = 0;
                    $arrayuser = array();
                    foreach ($modelsuser as $modeluser) {
                        $arrayuser[$n_user] = $modeluser->id;
                        $n_user++;
                    }
                }

                $modelsobject = Object::find()->where(['id_user' => $arrayuser])->select(['id', 'title'])->all();
            }
            if (Yii::$app->user->identity->role == 1) {
                $modelsobject = Object::find()->where(['id_user' =>  Yii::$app->user->identity->id])->select(['id', 'title'])->all();

            }
            if ($modelsobject != NULL) {
                foreach ($modelsobject as $modelobject) {
                    $arrayobject[$modelobject->id] = $modelobject->title;
                }
            }
            
            
            if ($modelrouter->load(Yii::$app->request->post())) {
                
                if ($modelrouter->save()) {
                    Yii::$app->session->setFlash('info', 'Роутер сохранен.');
                    return $this->redirect(['router', 'id' => $modelrouter->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Ройтер не сохранен.');
                    return $this->refresh();
                }
                
            } else {
                return $this->render('editrouter', [
                    'model' => $modelrouter,
                    'arrayobject' => $arrayobject,
                ]);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }


        public function actionRouters(){
        if (!\Yii::$app->user->isGuest) {
            $sort = new Sort([
                    'attributes' => [
                        'id' =>[
                            'label' => 'ID',
                        ],
                        'title' =>[
                            'asc' => ['title' => SORT_ASC],
                            'desc' => ['title' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'Название',
                        ],
                        'id_object' =>[
                            'asc' => ['id_object' => SORT_ASC],
                            'desc' => ['id_object' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'ID объекта',
                        ],
                        
                        'created_at' =>[
                            'label' => 'Дата регистрации',
                        ],
                        'status' =>[
                            'label' => 'Статус',
                        ],
                        
                        
                    ],
                ]);
                $arrayobject = array();
                if (Yii::$app->user->identity->role == 1) {
                    $modelsobject = Object::find()->where(['id_user' =>  Yii::$app->user->identity->id])->select(['id', 'title'])->all();
                    $n_object = 0;
                    if ($modelsobject != NULL) {
                        foreach ($modelsobject as $modelobject) {
                            $arrayobject[$n_object] = $modelobject->id;
                            $n_object++;
                        }
                    }

                    $query = Router::find()->where(['id_object' => $arrayobject])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 5) {
                    $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                    
                    if ($modelsuser != NULL) {
                        $n_user = 0;
                        $arrayuser = array();
                        foreach ($modelsuser as $modeluser) {
                            $arrayuser[$n_user] = $modeluser->id;
                            $n_user++;
                        }
                    }
                    $modelsobject = Object::find()->where(['id_user' =>  $arrayuser])->select(['id', 'title'])->all();
                    $n_object = 0;
                    if ($modelsobject != NULL) {
                        foreach ($modelsobject as $modelobject) {
                            $arrayobject[$n_object] = $modelobject->id;
                            $n_object++;
                        }
                    }

                    $query = Router::find()->where(['id_object' => $arrayobject])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 10) {
                    $query = Router::find()->orderBy($sort->orders);
                }
                $countQuery = clone $query;
                $pages = new Pagination(['totalCount' => $countQuery->count()]);
                $modelsrouter = $query->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();

                

                    return $this->render('routers', [
                        'modelsrouter' => $modelsrouter,
                        'pages' => $pages,
                        'sort' => $sort,
                    ]);

        }
        else{
            return $this->redirect(['login']);
        }
    }

        /**
     * Deletes an existing Router model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleterouter($id)
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 10) {
                $this->findModelrouter($id)->delete();
                Yii::$app->session->setFlash('info', 'Роутер удален.');
                return $this->redirect(['routers']);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }


     /**
     * Finds the Router model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Monitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelrouter($id)
    {
        if (($modelrouter = Router::findOne($id)) !== null) {
            return $modelrouter;
        } else {
            
            throw new NotFoundHttpException('Ройтер не найден.');
        }
    }
    //CRUD router

    //CRUD post
    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatepost()
    {
        if (!\Yii::$app->user->isGuest) {
            $modelpost = new Post();
            $arrayrouter = array();
            if (Yii::$app->user->identity->role == 10) {
                $modelsrouter = Router::find()->select(['id', 'title'])->all();

            }
            if (Yii::$app->user->identity->role == 5) {
                $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                if ($modelsuser != NULL) {
                    $n_user = 0;
                    $arrayuser = array();
                    foreach ($modelsuser as $modeluser) {
                        $arrayuser[$n_user] = $modeluser->id;
                        $n_user++;
                    }
                }

                $modelsobject = Object::find()->where(['id_user' => $arrayuser])->select(['id', 'title'])->all();

                $n_object = 0;
                $arrayobject = array();
                if ($modelsobject != NULL) {
                    foreach ($modelsobject as $modelobject) {
                        $arrayobject[$n_object] = $modelobject->id;
                        $n_object++;
                    }
                }
                $modelsrouter = Router::find()->where(['id_object' => $arrayobject])->select(['id', 'title'])->all();

            }
            if (Yii::$app->user->identity->role == 1) {
                $modelsobject = Object::find()->where(['id_user' =>  Yii::$app->user->identity->id])->select(['id', 'title'])->all();

                $n_object = 0;
                $arrayobject = array();
                if ($modelsobject != NULL) {
                    foreach ($modelsobject as $modelobject) {
                        $arrayobject[$n_object] = $modelobject->id;
                        $n_object++;
                    }
                }
                $modelsrouter = Router::find()->where(['id_object' => $arrayobject])->select(['id', 'title'])->all();

            }
            if ($modelsrouter != NULL) {
                foreach ($modelsrouter as $modelrouter) {
                    $arrayrouter[$modelrouter->id] = $modelrouter->title;
                }
            }
            
            
            if ($modelpost->load(Yii::$app->request->post())) {
                
                if ($modelpost->save()) {
                    Yii::$app->session->setFlash('info', 'Пост добавлен.');
                    return $this->redirect(['post', 'id' => $modelpost->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Пост не создан.');
                    return $this->refresh();
                }
                
            } else {
                return $this->render('createpost', [
                    'model' => $modelpost,
                    'arrayrouter' => $arrayrouter,
                ]);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }

    /**
     * Edit a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPost($id)
    {
        if (!\Yii::$app->user->isGuest) {
            $modelpost = $this->findModelpost($id);
            $arrayrouter = array();
            if (Yii::$app->user->identity->role == 10) {
                $modelsrouter = Router::find()->select(['id', 'title'])->all();

            }
            if (Yii::$app->user->identity->role == 5) {
                $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                if ($modelsuser != NULL) {
                    $n_user = 0;
                    $arrayuser = array();
                    foreach ($modelsuser as $modeluser) {
                        $arrayuser[$n_user] = $modeluser->id;
                        $n_user++;
                    }
                }

                $modelsobject = Object::find()->where(['id_user' => $arrayuser])->select(['id', 'title'])->all();

                $n_object = 0;
                $arrayobject = array();
                if ($modelsobject != NULL) {
                    foreach ($modelsobject as $modelobject) {
                        $arrayobject[$n_object] = $modelobject->id;
                        $n_object++;
                    }
                }
                $modelsrouter = Router::find()->where(['id_object' => $arrayobject])->select(['id', 'title'])->all();

            }
            if (Yii::$app->user->identity->role == 1) {
                $modelsobject = Object::find()->where(['id_user' =>  Yii::$app->user->identity->id])->select(['id', 'title'])->all();

                $n_object = 0;
                $arrayobject = array();
                if ($modelsobject != NULL) {
                    foreach ($modelsobject as $modelobject) {
                        $arrayobject[$n_object] = $modelobject->id;
                        $n_object++;
                    }
                }
                $modelsrouter = Router::find()->where(['id_object' => $arrayobject])->select(['id', 'title'])->all();

            }
            if ($modelsrouter != NULL) {
                foreach ($modelsrouter as $modelrouter) {
                    $arrayrouter[$modelrouter->id] = $modelrouter->title;
                }
            }
            
            
            if ($modelpost->load(Yii::$app->request->post())) {
                
                if ($modelpost->save()) {
                    Yii::$app->session->setFlash('info', 'Пост сохранен.');
                    return $this->redirect(['router', 'id' => $modelpost->id]);
                }
                else{
                    Yii::$app->session->setFlash('error', 'Пост не хранен.');
                    return $this->refresh();
                }
                
            } else {
                return $this->render('editpost', [
                    'model' => $modelpost,
                    'arrayrouter' => $arrayrouter,
                ]);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }

    public function actionPosts(){
        if (!\Yii::$app->user->isGuest) {
            $sort = new Sort([
                    'attributes' => [
                        'id' =>[
                            'label' => 'ID',
                        ],
                        'urlimg' =>[
                            'asc' => ['urlimg' => SORT_ASC],
                            'desc' => ['urlimg' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'Картинка',
                        ],
                        'id_router' =>[
                            'asc' => ['id_router' => SORT_ASC],
                            'desc' => ['id_router' => SORT_DESC],
                            'default' => SORT_ASC,
                            'label' => 'ID Роутора',
                        ],
                        
                        'created_at' =>[
                            'label' => 'Дата создания',
                        ],
                        'status' =>[
                            'label' => 'Статус',
                        ],
                        
                        
                    ],
                ]);
                $arrayobject = array();
                $arrayrouter = array();
                if (Yii::$app->user->identity->role == 1) {
                    $modelsobject = Object::find()->where(['id_user' =>  Yii::$app->user->identity->id])->select(['id', 'title'])->all();
                    $n_object = 0;
                    if ($modelsobject != NULL) {
                        foreach ($modelsobject as $modelobject) {
                            $arrayobject[$n_object] = $modelobject->id;
                            $n_object++;
                        }
                    }

                    $modelsrouter = Router::find()->where(['id_object' => $arrayobject])->select(['id'])->all();
                    $n_router = 0;
                    if ($modelsrouter != NULL) {
                        foreach ($modelsrouter as $modelrouter) {
                            $arrayrouter[$n_router] = $modelrouter->id;
                            $n_router++;
                        }
                    }
                    $query = Post::find()->where(['id_router' => $arrayrouter])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 5) {
                    $modelsuser = Users::find()->where(['role' => 1, 'id_manager' => Yii::$app->user->identity->id])->select(['id'])->all();
                    
                    if ($modelsuser != NULL) {
                        $n_user = 0;
                        $arrayuser = array();
                        foreach ($modelsuser as $modeluser) {
                            $arrayuser[$n_user] = $modeluser->id;
                            $n_user++;
                        }
                    }
                    $modelsobject = Object::find()->where(['id_user' =>  $arrayuser])->select(['id', 'title'])->all();
                    $n_object = 0;
                    if ($modelsobject != NULL) {
                        foreach ($modelsobject as $modelobject) {
                            $arrayobject[$n_object] = $modelobject->id;
                            $n_object++;
                        }
                    }

                    $modelsrouter = Router::find()->where(['id_object' => $arrayobject])->select(['id'])->all();
                    $n_router = 0;
                    if ($modelsrouter != NULL) {
                        foreach ($modelsrouter as $modelrouter) {
                            $arrayrouter[$n_router] = $modelrouter->id;
                            $n_router++;
                        }
                    }

                    $query = Post::find()->where(['id_router' => $arrayrouter])->orderBy($sort->orders);
                }
                if (Yii::$app->user->identity->role == 10) {
                    $query = Post::find()->orderBy($sort->orders);
                }
                $countQuery = clone $query;
                $pages = new Pagination(['totalCount' => $countQuery->count()]);
                $modelspost = $query->offset($pages->offset)
                    ->limit($pages->limit)
                    ->all();

                

                    return $this->render('posts', [
                        'modelspost' => $modelspost,
                        'pages' => $pages,
                        'sort' => $sort,
                    ]);

        }
        else{
            return $this->redirect(['login']);
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletepost($id)
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->user->identity->role == 10) {
                $this->findModelpost($id)->delete();
                Yii::$app->session->setFlash('info', 'Пост удален.');
                return $this->redirect(['posts']);
            }
        }
        else{
            return $this->redirect(['login']);
        }
    }    


    /**
     * Finds the post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Monitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelpost($id)
    {
        if (($modelpost = Post::findOne($id)) !== null) {
            return $modelpost;
        } else {
            
            throw new NotFoundHttpException('Пост не найден.');
        }
    }    
    //CRUD post

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
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Monitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelprofile($id)
    {
        if (($modelprofile = Users::findOne($id)) !== null) {
            return $modelprofile;
        } else {
            
            throw new NotFoundHttpException('Пользователь(профиль) не найден.');
        }
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
        if (($modeluser = User::findOne($id)) !== null) {
            return $modeluser;
        } else {
            
            throw new NotFoundHttpException('Пользователь не найден.');
        }
    }

   

    

   
}
