<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Редактирование заведения: '.$model->title;
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
                                <div class="col-md-6 col-md-offset-3">
                                    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
                                    <div class="form">
                                        <?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  <?= $form->field($model, 'title')->textInput(['placeholder' => 'Название заведения', 'class' => 'form-control'])->label(false) ?>
                                                </div>
                                        </div>
<?php
if (Yii::$app->user->identity->role >= 5) {
    
?>
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  <?= $form->field($model, 'id_user')->dropDownList($arrayuser)->label(false) ?>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  <?= $form->field($model, 'status')->dropDownList([5 => 'Заблокировано', 10 => 'Активное'])->label(false) ?>
                                                </div>
                                        </div>
<?php
}
?>                                        
                                        
                                        <div class="form-group">
                                              <div class="col-md-12 text-center"> 
                                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-default', 'name' => 'profile-button']) ?>
                                              </div>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                </div>
                            </div>
                      


                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


</body>