<?php
namespace app\models;

use yii\db\ActiveRecord;

class Guards extends ActiveRecord //ActiveRecord - это встроенный во фреймворк класс работы с таблицами
{ 
    //обработка данных
    public static function tableName()
    {
        return 'guards';
    }
    ///public $clients;

}
?>


