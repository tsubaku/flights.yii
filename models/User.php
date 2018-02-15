<?php
// http://rgblog.ru/page/sozdanie-sajta-na-yii-framework-20-chast-2 -тут правильные функции для модели User
namespace app\models;

use yii\db\ActiveRecord;           
use yii\web\IdentityInterface;      
use yii\base\NotSupportedException;
use Yii;

/**
 * Таблица пользователей.
 */
class User extends ActiveRecord implements IdentityInterface
{
    #Проверка прав пользователя
    const ROLE_ADMIN     = 20;  
    const ROLE_OPERATOR  = 15;
    const ROLE_USER      = 10; 
   
    public function rules() {
        return [
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]], 
        ];  
    }
    //Проверяем, имеется ли в базе указанный юзер с правами админа
    public static function isUserAdmin($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN]))
        {
            return true;
        } else {
            return false;
        }
    }
    //Проверяем, имеется ли в базе указанный юзер с правами Оператор
    public static function isUserOperator($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_OPERATOR]))
        {
            return true;
        } else {
            return false;
        }
    }
    //Проверяем, имеется ли в базе указанный юзер с правами Юзер (охранник)
    public static function isUserUser($username)
    {
        if (static::findOne(['username' => $username, 'role' => self::ROLE_USER]))
        {
            return true;
        } else {
            return false;
        }
    }
    
   
   /*  public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken; */


    //Связать контроллер с базой с произвольным именем
    /*  public static function tableName() {
        return 'user';
    }  */

    //Пример работы с юзерами без занесения их в бд
    /* private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ]; */

        
    /**
     * @inheritdoc
     */
    //Этот метод находит экземпляр identity class, используя ID пользователя. Этот метод используется, когда необходимо поддерживать состояние аутентификации через сессии.
    public static function findIdentity($id)
    {
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
       ////! return static::findOne($username);     //вот из-за этого разлогинивает
        return static::findOne(['id' => $id]);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /*! foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }
        return null; */
        
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.'); //так в правильном мане, но и верхний вариант на разлогинивание не влияют
    }
    
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        /* foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }
        return null; */
        return static::findOne(['username' => $username]);
    }

    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
     //// return $this->getPrimaryKey(); //так в правильном мане, но и $this->id на разлогинивание не влияют
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return $this->password === $password;
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
    
}
