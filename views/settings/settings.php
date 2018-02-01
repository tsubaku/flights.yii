<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use vova07\imperavi\Widget;     //Imperavi Redactor Widget
$this->title = 'Настройки';


?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="container-fluid"> 
        <div class="row">
            <!-- form Регистрация клиентов --->
            <?php $form = ActiveForm::begin([
                    'id' => 'settingsForm',
                    'layout' => 'inline',
                    'fieldConfig' => [
                        'labelOptions' => ['class' => ''],
                        'enableError' => true,
                    ]
                ]) ?>


            <?= $form->field($model, 'content', [
                    'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
                ]) 
                
            ?>
            
                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'id' => 'saveSentryHeaderText', 'name' => 'saveSentryHeaderText']) ?>
                    </div>
                </div>
            <?php ActiveForm::end() ?> 
            <!--    </form>     --> 
        </div><!-- row -->

            
        

    </div> <!-- container-fluid -->
        
        
        
    <br />


        

    <div id="status">					
    </div>


    <?php


    echo '<pre>'; 
    //print_r ($gun->name); //фактически - последний клиент из списка
    print_r ($sentryHeaderText);
    //print_r ($rows);
    echo '</pre>'; 

    ?>        

 
    
    
</div>
