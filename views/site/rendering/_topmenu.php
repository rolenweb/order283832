<?php
use yii\helpers\Html;
?>
		<div id="user-account" class="row" >
            <div class="threecol">
                <span>Welcome to <?= Yii::$app->name ?> Dashboard</span>
            </div>
             <div class="ninecol last">
                <?= Html::a('Выход', ['logout'], ['data-method' => 'post'])?> <span>|</span> <?= Html::a('Профиль', ['profile', 'id' => Yii::$app->user->identity->id]) ?> <span>|</span> <span>Добро пожаловать, <strong><?= Html::encode(Yii::$app->user->identity->username) ?></strong></span>
            </div>
        </div>