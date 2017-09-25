<?php

namespace app\models;
use yii\base\Model;

class SignupForm extends Model{
 
 public $username;
 public $password;
 
 public function rules() {
 return [
 [['user_login', 'user_password'], 'required', 'message' => 'Заполните поле'],
 ];
 }
 
 public function attributeLabels() {
 return [
 'user_login' => 'Логин',
 'user_password' => 'Пароль',
 ];
 }
 
}
