<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = 'Пользователи';
?>
<body>

    <div id="header-wrapper" class="container-fluid">
        <?= $this->render('rendering/_topmenu'); ?>
        <?= $this->render('rendering/_headmenu'); ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            
            <?= $this->render('rendering/_sidebar'); ?>
            <div id="content" class="ninecol last">
                <div class="panel-wrapper">
                    <div class="site-index">

                        

                        <div class="body-content">
                            <?= $this->render('rendering/_flashmessage'); ?>
                            <div class="row">
                                <div class="col-sm-12 text-right">
                                    <?= Html::a('Добавить пользователя', ['signup'], ['class' => 'btn btn-default btn-xs']) ?>
                                </div>
                            </div>
                            <div class="row top10">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                      <thead>
                                        <tr>
                                          
                                          <th>
                                            <?= $sort->link('id') ?>
                                          </th>
                                          <th>
                                            <?= $sort->link('username') ?>
                                          </th>
<?php
if (Yii::$app->user->identity->role == 10) {
?>
                                          <th>
                                            <?= $sort->link('role') ?>
                                          </th>
                                          <th>
                                            <?= $sort->link('id_manager') ?>
                                          </th>

<?php
}
?>                                          
                                          <th>
                                            
                                            <?= $sort->link('created_at') ?>
                                          </th>
                                          <th>
                                            <?= $sort->link('status') ?>
                                          </th>
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                                                                   
                                          <th>
                                            Дествия
                                          </th>
<?php
}
?>                                          
                                        </tr>
                                      </thead>
                                      <tbody>
<?php
if ($modelsuser != NULL) {
    

foreach ($modelsuser as $modeluser) {
?>
                                        <tr>
                                          <th><?= Html::encode($modeluser->id) ?></th>
                                          <td><?= Html::encode($modeluser->username) ?></td>
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                          
                                          <td>
<?php
if ($modeluser->role == 1) {
    echo 'Пользователь';
}
if ($modeluser->role == 5) {
    echo 'Менеджер';
}
if ($modeluser->role == 10) {
    echo 'Админ';
}
?>                                              
                                          </td>
<?php
}
?>                                         
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                          
                                          <td>
<?php
foreach ($arraymanager as $key => $value) {
    if ($key == $modeluser->id_manager) {
?>
                                            <?= Html::a($value,['profile', 'id' => $key]) ?>
<?php
        break;
    }
    
}
?>                                
                                          </td>
<?php
}
?>
                                        <td>
                                            <?= Html::encode(date("d.m.y",$modeluser->created_at)) ?>
                                        </td>
                                        <td>
<?php
if ($modeluser->status == 5) {
    echo "Заблокирован";
}
if ($modeluser->status == 10) {
    echo "Активный";
}
?>                                        
                                            
                                        </td>
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                         
                                        <td>
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-eye']),['profile', 'id' => $modeluser->id]) ?>

                                            &nbsp; / &nbsp;

                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-trash-o']),['deleteuser', 'id' => $modeluser->id], ['data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить пользователя?',
                                                    'method' => 'post',
                                                    ],
                                                ]) ?>

                                        </td>
<?php
}
?>                                                                                    
                                        </tr>
<?php    
}
}
?>                                      

                                        
                                        
                                      </tbody>
                                    </table>
                                  </div>
                                
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


</body>