<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Таблица табельного оружия, находящегося на балансе организации
 */
class Gun extends ActiveRecord //ActiveRecord - это встроенный во фреймворк класс работы с таблицами
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
            ['name', 'unique', 'message' => 'Такое оружие уже есть в списке'],
            //['name', 'safe'],
            ['name', 'required', 'message' => 'Заполните поле'],
        ];
    }
    
    
    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public function attributeLabels() {
        return [
            'name' => 'Оружие',
        ];
    }

}
?>


