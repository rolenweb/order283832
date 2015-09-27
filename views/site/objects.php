<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = 'Заведения';
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
                'text' => 'Заведений'
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
                                    <?= Html::a('Добавить заведение', ['createobject'], ['class' => 'btn btn-default btn-xs']) ?>
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
                                            <?= $sort->link('id_user') ?>
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
if ($modelsobject != NULL) {
    

foreach ($modelsobject as $modelobject) {
?>
                                        <tr>
                                          <th><?= Html::encode($modelobject->id) ?></th>
                                          <td><?= Html::encode($modelobject->title) ?></td>
                                        <td>
                                            <?= Html::encode($modelobject->id_user) ?>
                                        </td>
                                        <td>
                                            <?= Html::encode(date("d.m.y",$modelobject->created_at)) ?>
                                        </td>
                                        <td>
<?php
if ($modelobject->status == 5) {
    echo "Заблокирован";
}
if ($modelobject->status == 10) {
    echo "Активный";
}
?>                                        
                                            
                                        </td>

                                        <td>
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-eye']),['object', 'id' => $modelobject->id]) ?>
<?php
if (Yii::$app->user->identity->role == 10) {
?>                                         
                                            &nbsp; / &nbsp;
                                            <?= Html::a(Html::tag('i','',['class' => 'fa fa-trash-o']),['deleteobject', 'id' => $modelobject->id], ['data' => [
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