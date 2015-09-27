<?php
use yii\helpers\Html;
?>
		<div id="user-options" class="row">
            <div class="threecol">
                    <?= Html::a(Html::img('@web/_layout/images/back-logo.png', ['class' => 'logo', 'alt' => Yii::$app->name.' Dashboard']), ['index']) ?>
            </div>
            <div class="ninecol last fixed">
				<ul class="nav-user-options">
<?php
if (Yii::$app->user->identity->role >= 5) {
?>				
					
					<li>
						
						<?= Html::a(Html::img('@web/_layout/images/icons/icon-menu-users.png',['alt' => 'Пользователи']).'&nbsp; Пользователи',['users']) ?>
					</li>
<?php    
}
?>					
				</ul>
			</div>
            
        </div>