<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';

?>
<body class="texture">
    <div class="container">
        <div class="row">
            <div class="panel-wrapper panel-login">
                <div class="panel">
                    <div class="title">
                    <h4>User Login</h4>
                    <!--<div class="option">Sign up for free &raquo;</div>-->
                    </div>
                    <div class="content">
                        <!-- ## Panel Content  -->
                        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                        
                                
                                
                                  <?= $form->field($model, 'username')->textInput(['placeholder' => 'Логин', 'class' => 'form-control'])->label(false) ?>
                                
                        
                                
                                
                                  
                                  <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль', 'class' => 'form-control'])->label(false) ?>
                                
                        
                              
                                <?= Html::submitButton('Войти', ['class' => 'button-blue submit', 'name' => 'signup-button']) ?>
                              
                        
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="shadow"></div>
            </div>
            <div class="login-details">
                <p>Forgot your password? &nbsp;&nbsp;&nbsp;<a href="#">Click here</a></p>
            </div>
        </div>
     </div>
</body>