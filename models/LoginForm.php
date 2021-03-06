<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;   //атрибуты
    public $password;
    public $rememberMe = true;

    private $user = false;


    /**
     * @return array the validation rules.
     */
    //Методы rules() и прочие:
    //rules() -правила валидации
    public function rules()
    {
        return [
            // username and password are both required
            // username и password - имена полей ввода во вью login.php
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    //attributeLabels() -устанавливаете названия полей для тега label формы. Эти названия совпадают с именами атрибутов модели
    public function attributeLabels() {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        //hasErrors() -Возвращает значение, указывающее, есть ли какая-либо ошибка проверки.
        if (!$this->hasErrors()) {
            //getUser() -Returns the user component.
            $user = $this->getUser();
            //Если $user нет ИЛИ валидация с введённым паролем не прошла
            if (!$user || !$user->validatePassword($this->password)) {
                //addError() -Добавляет ошибку к указанному атрибуту объекту модели.
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    
    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            //return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->user === false) {
            //$this->user = User::findByUsername($this->username);  //было
            
            //$this->user = User::findOne(['username'=>$this->username, 'password'=>$this->password]);
            $this->user = User::findOne(['username'=>$this->username]);
        }

        return $this->user;
    }
    
    #Проверка прав пользователя
    //Проверка на админа
    public function loginAdmin()
    {
        if ($this->validate() && User::isUserAdmin($this->username)) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    //Проверка на оператора
    public function loginOperator()
    {
        if ($this->validate() && User::isUserOperator($this->username)) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    //юзера (охранника)
    public function loginUser()
    {
        if ($this->validate() && User::isUserUser($this->username)) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
}
