<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */

$this->title = 'Редактивание поста '.$model->id;
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
                                <div class="col-md-12">
                                    <h3 class="text-center"><?= Html::encode($this->title) ?></h3>
                                    <div class="form">
                                        <?php $form = ActiveForm::begin(['id' => 'form-profile']); ?>
                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  <?= $form->field($model, 'urlimg')->textInput(['placeholder' => 'Урл картинки', 'class' => 'form-control'])->label(false) ?>
                                                </div>
                                        </div>

                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  <?= $form->field($model, 'id_router')->dropDownList($arrayrouter)->label(false) ?>
                                                </div>
                                        </div>

                                        <div class="form-group">
                                                
                                                <div class="col-md-12">
                                                  <?= $form->field($model, 'text')->widget(TinyMce::className(), [
    'options' => ['rows' => 12],
    'language' => 'ru',
    'clientOptions' => [
        'plugins' => [
            "advlist autolink lists link charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media image table contextmenu paste"
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        'image_advtab' => true,
    ]
])->label(false); ?>
                                                </div>
                                        </div>
<?php
if (Yii::$app->user->identity->role = 10) {
    
?>
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