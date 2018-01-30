<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Настройки';

$table_users = '31'; //клиенты             !!! Костыль !!!
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="container-fluid"> 
        <div class="row">
            <!-- form Регистрация клиентов --->
            <?php $form = ActiveForm::begin([
                    'id' => 'add',
                    'layout' => 'inline',
                    'fieldConfig' => [
                        'labelOptions' => ['class' => ''],
                        'enableError' => true,
                    ]
                ]) ?>


            <?= $form->field($model, 'name', [
                    'template' => '{label} <div class="row"><div class="col-sm-2">{input}{error}{hint}</div></div>'
                ]) 
                
            ?>
            
                <div class="form-group">
                    <div>
                        <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success', 'id' => 'add1', 'name' => 'add2']) ?>
                    </div>
                </div>
            <?php ActiveForm::end() ?> 
            <!--    </form>     --> 
        </div><!-- row -->

            
        <div class="row">    
            <?php
                echo \vova07\imperavi\Widget::widget([
                    'name' => 'redactor',
                    'settings' => [
                        'lang' => 'ru',
                        'minHeight' => 200,
                        'plugins' => [
                            'clips',
                            'fullscreen',
                        ],
                    ],
                ]);
            ?>
        </div> <!-- row -->

    </div> <!-- container-fluid -->
        
        
        
    <br />


        

    <div id="status">					
    </div>


    <?php


    //echo '<pre>'; 
    //print_r ($gun->name); //фактически - последний клиент из списка
    //print_r ($gun);
    //print_r ($rows);
    //echo '</pre>'; 

    ?>        

 
    
    
</div>
