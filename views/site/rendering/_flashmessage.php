              <?php if (Yii::$app->session->hasFlash('info')){ ?>
        
                  <div class="alert alert-success">
                      <?= Yii::$app->getSession()->getFlash('info') ?>
                  </div>
                <?php
                 }  
                 if (Yii::$app->session->hasFlash('error')){ ?>
        
                  <div class="alert alert-danger">
                      <?= Yii::$app->getSession()->getFlash('error') ?>
                  </div>
                <?php
                 }  
                 
                 ?>  