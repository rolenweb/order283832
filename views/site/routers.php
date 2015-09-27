<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = 'Роутеры';
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
                'text' => 'Роуторы'
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
                                    <?= Html::a('Добавить роутер', ['createrouter'], ['class' => 'btn btn-default btn-xs']) ?>
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
                                            <?= $sort->link('title') ?>
                                          </th>
                                          <th>
                                            <?= $sort->link('id_object') ?>
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
if ($modelsrouter != NULL) {
    

foreach ($modelsrouter as $modelrouter) {
?>
                                        <tr>
                                          <th><?= Html::encode($modelrouter->id) ?></th>
                                          <td><?= Html::encode($modelrouter->title) ?></td>
                                        <td>
                                            <?= Html::encode($modelrouter->id_object) ?>
                                        </td>
                                        <td>
                                            <?= Html::encode(date("d.m.y",$modelrouter->created_at)) ?>
                                        </td>
                                        <td>
<?php
if ($modelrouter->status == 5) {
    echo "Заблокирован";
}
if ($modelrouter->status == 10) {
    echo "Активный";
}
?>                                        
                                            
                                        </td>

                                        <td>
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-eye']),['router', 'id' => $modelrouter->id]) ?>
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                         
                                            &nbsp; / &nbsp;
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-trash-o']),['deleterouter', 'id' => $modelrouter->id], ['data' => [
                                                    'confirm' => 'Вы уверены, что хотите удалить заведение?',
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