<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = 'Посты';
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
                              <div class="col-sm-12">
                                <?=
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'line'
        ],
        'title' => [
             'text' => 'Статистика за месяц'
             ],
        'xAxis' => [
            'categories' => [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
            ]
        ],
        'yAxis' => [
            'title' => [
                'text' => 'Посты'
            ]
        ],
        'series' => [
            ['name' => 'День', 'data' => [112,212,412,123,123,321,213]],
            
        ]
    ]
]);
?>
                              </div>
                            </div>
                            <div class="row top10">
                                <div class="col-sm-12 text-right">
                                    <?= Html::a('Добавить пост', ['createpost'], ['class' => 'btn btn-default btn-xs']) ?>
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
                                            <?= $sort->link('urlimg') ?>
                                          </th>
                                          <th>
                                            <?= $sort->link('id_router') ?>
                                          </th>
                                          <th>
                                            
                                            <?= $sort->link('created_at') ?>
                                          </th>
                                          <th>
                                            <?= $sort->link('status') ?>
                                          </th>

                                          <th>
                                            Дествия
                                          </th>

                                        </tr>
                                      </thead>
                                      <tbody>
<?php
if ($modelspost != NULL) {
    

foreach ($modelspost as $modelpost) {
?>
                                        <tr>
                                          <th><?= Html::encode($modelpost->id) ?></th>
                                          <td><?= Html::encode($modelpost->urlimg) ?></td>
                                        <td>
                                            <?= Html::encode($modelpost->id_router) ?>
                                        </td>
                                        <td>
                                            <?= Html::encode(date("d.m.y",$modelpost->created_at)) ?>
                                        </td>
                                        <td>
<?php
if ($modelpost->status == 5) {
    echo "Заблокирован";
}
if ($modelpost->status == 10) {
    echo "Активный";
}
?>                                        
                                            
                                        </td>

                                        <td>
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-eye']),['post', 'id' => $modelpost->id]) ?>
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                            
                                         
                                            &nbsp; / &nbsp;
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-trash-o']),['deletepost', 'id' => $modelpost->id], ['data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить пост?',
                                                    'method' => 'post',
                                                    ],
                                                ]) ?>
<?php
}
?>                                                 
                                                                                   
                                        </td>

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