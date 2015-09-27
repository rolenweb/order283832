<?php
use yii\helpers\Html;
?>
		<div id="sidebar" class="threecol">
                    <ul id="navigation">
                        <li class="first active">
                            
                            <?= Html::a('Панель управления '.Html::tag('i','',['class' => 'icon-dashboard']), ['index']) ?>
                        </li>
                       

                        <li>
                            
                            <?= Html::a('Заведения '.Html::tag('i','',['class' => 'icon-tables']), ['objects']) ?>
                        </li>
                        <li>
                            <?= Html::a('Роутеры '.Html::tag('i','',['class' => 'icon-tables']), ['routers']) ?>
                        </li>
                        <li>
                            <?= Html::a('Посты '.Html::tag('i','',['class' => 'icon-tables']), ['posts']) ?>
                        </li>
                        
                    </ul>
            </div>