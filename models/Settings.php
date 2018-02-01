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
    //public $sentryHeaderText;

    
    public function rules() {
        return [
            //['name', 'unique', 'message' => 'Такое оружие уже есть в списке'],
            ['content', 'safe'],
            //['name', 'required', 'message' => 'Заполните поле'],
            //['name', 'unique', 'targetClass' => User::className(),  'message' => 'Такой клиент уже существует'],
        ];
    }
    
    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public function attributeLabels() {
        return [
            'content' => 'Шапка',
        ];
    }
    
    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public static function getCompanyName() {
        //$companyName=Settings::model()->findByPk($id);
        $companyName=Settings::find()->select('content')->where(['name' => 'companyName'])->asArray()->column();
        //if ($user!==NULL){
            //return $user->name;  
            return $companyName;  
        //} else return false;
    }


}
?>


