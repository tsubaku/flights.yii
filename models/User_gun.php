<?php
// http://rgblog.ru/page/sozdanie-sajta-na-yii-framework-20-chast-2 -тут правильные функции для модели User
namespace app\models;

//class User extends \yii\base\Object implements \yii\web\IdentityInterface
use yii\db\ActiveRecord;            //без этого


class User_gun extends ActiveRecord
{
    public function getGun() {  //get - обязательная приставка
        return $this->hasMany(Gun::className(), ['id' => 'gun_id']);  
        //1-имя класса, 2-массив, где кючами является поле связывемой таблицы Gun, а значениями - поля основной таблицы User_gun (className()-возвращает строку с именем класса)
    }
     public function getUser() {  //get - обязательная приставка
        return $this->hasMany(User::className(), ['id' => 'user_id']);  
    }
    
}

