<?php
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'RolenWeb Admin';
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
             'text' => 'Статистика подключений заведений за месяц'
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
                              <div class="col-sm-12">
                                <?=
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'line'
        ],
        'title' => [
             'text' => 'Статистика подключений роутеров за месяц'
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
                'text' => 'Роутеры'
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
                              <div class="col-sm-12">
                                <?=
\dosamigos\highcharts\HighCharts::widget([
    'clientOptions' => [
        'chart' => [
                'type' => 'line'
        ],
        'title' => [
             'text' => 'Статистика постов заведений за месяц'
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

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


</body>