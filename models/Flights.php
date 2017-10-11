<?php
namespace app\models;

use yii\db\ActiveRecord;

class Flights extends ActiveRecord //ActiveRecord - это встроенный во фреймворк класс работы с таблицами
{ 
    //обработка данных
    //public static function tableName()
    //{
    //    return 'users';
    //}
    ///public $clients;
    
    
    //rules() -правила валидации
    public function rules()
    {
        return [
            // username and password are both required
            //кажется, username и password - имена полей ввода во вью login.php
       ///     [['month', 'year'], 'required'],
            // rememberMe must be a boolean value
            [['month', 'year'], 'safe'],
        ];
    }
    
    
}
?>


