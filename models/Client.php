<?php
namespace app\models;

use yii\db\ActiveRecord;

class Client extends ActiveRecord //ActiveRecord - это встроенный во фреймворк класс работы с таблицами
{ 
    //обработка данных
    //public static function tableName()
    //{
    //    return 'clients';
   // }
    ///public $clients;
    
    public function rules() {
        return [
            //['client', 'unique'],
            ['client', 'safe'],
           // ['client', 'required', 'message' => 'Заполните поле'],
           // ['client', 'unique', 'targetClass' => User::className(),  'message' => 'Такой клиент уже существует'],
        ];
    }

}
?>


