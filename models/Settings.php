<?php
namespace app\models;

use yii\db\ActiveRecord;

//use yii\web\IdentityInterface;      //идут ошибки
//use yii\base\NotSupportedException;
//use Yii;

class Settings extends ActiveRecord //ActiveRecord - это встроенный во фреймворк класс работы с таблицами
//class Gun extends ActiveRecord implements IdentityInterface
{ 
    //обработка данных
    //public static function tableName()
    //{
    //    return 'clients';
    //}
    ///public $clients;

    
    public function rules() {
        return [
            //['name', 'unique', 'message' => 'Такое оружие уже есть в списке'],
            //['name', 'safe'],
            //['name', 'required', 'message' => 'Заполните поле'],
            //['name', 'unique', 'targetClass' => User::className(),  'message' => 'Такой клиент уже существует'],
        ];
    }
    
    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public function attributeLabels() {
        return [
            //'name' => 'Оружие',
        ];
    }

}
?>


