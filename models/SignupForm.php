<?php
namespace app\models;

//use yii\db\ActiveRecord;
//class Users extends ActiveRecord //ActiveRecord - это встроенный во фреймворк класс работы с таблицами
use yii\base\Model;             //так в примере
class SignupForm extends Model
{ 
    //обработка данных
    //public static function tableName()
    //{
    //    return 'users';
    //}
    ///public $clients;
    public $username;     //атрибуты модели (совпадают с именами полей таблицы users)
    public $password;  //они же свойства модели или поля модели
    public $full_name;  

    //Методы модели
    //rules() -правила валидации
    public function rules() {
        return [
            [['username', 'password', 'full_name'], 'required', 'message' => 'Заполните поле'],
            ['username', 'unique', 'targetClass' => User::className(),  'message' => 'Этот логин уже занят'],
        ];  //Поля 'username' и 'password' обязательны. Названия полей совпадают с названиями атрибутов модели
            //Содержимое поля username должно быть уникальным
    }

 
    
    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'full_name' => 'Полное имя',
        ];
    }
}
?>


