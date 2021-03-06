<?php
namespace app\models;

use yii\base\Model;  

/**
 * Таблица пользователей.
 */          
class SignupForm extends Model
{ 
    public $username;  //атрибуты модели (совпадают с именами полей таблицы users)
    public $password;  //они же свойства модели или поля модели
    public $full_name;  
    public $department;  

    //Методы модели
    //rules() -правила валидации
    public function rules() {
        return [
            [['username', 'password', 'full_name', 'department'], 'required', 'message' => 'Заполните поле'],
            [['username', 'full_name'], 'unique', 'targetClass' => User::className(),  'message' => 'Этот логин уже занят'],
        ];  //Поля 'username' и 'password' обязательны. Названия полей совпадают с названиями атрибутов модели
            //Содержимое поля username должно быть уникальным
    }


    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public function attributeLabels() {
        return [
            'username'      => 'Логин',
            'password'      => 'Пароль',
            'full_name'     => 'Полное имя',
            'department'    => 'Отдел',
        ];
    }

}
?>


