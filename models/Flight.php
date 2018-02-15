<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Таблица рейсов охранников.
 */
class Flight extends ActiveRecord 
{ 
    //обработка данных (явно указываем название таблицы)
    public static function tableName()
    {
        return 'flight';
    }
    
    
    //rules() -правила валидации
    public function rules()
    {
        return [
            // username and password are both required
            // username и password - имена полей ввода во вью login.php
            ///[['month', 'year'], 'required'],
            [['month', 'year'], 'safe'],
        ];
    }
    
    //Вернуть пути к сохранённым на сервере фотографиям с рейса №n_flight, если они есть
    public function getPhoto() //get - обязательная приставка
    {  
        return $this->hasMany(Photo::className(), ['n_flight' => 'id']);
    }
    
    
}
?>


