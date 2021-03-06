<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'Сменить пароль';
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
                                    <h3 class="text-center">Смена пароля пользователя <?= Html::encode($model->username) ?></h3>
                                    <div class="form">
                                        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                                        
                                        
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  
                                                  <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Старый пароль', 'class' => 'form-control'])->label(false) ?>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  
                                                  <?= $form->field($model, 'new_password')->passwordInput(['placeholder' => 'Новый пароль', 'class' => 'form-control'])->label(false) ?>
                                                </div>
                                        </div>
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  
                                                  <?= $form->field($model, 'new_password_repeat')->passwordInput(['placeholder' => 'Введите новый пароль еще раз', 'class' => 'form-control'])->label(false) ?>
                                                </div>
                                        </div>


                                        
                                       
                                        <div class="form-group">
                                              <div class="col-md-12 text-center"> 
                                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-default', 'name' => 'signup-button']) ?>
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