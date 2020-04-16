<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $passwordAgain;
    public $name;
    public $surname;
    public $middlename;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Такой логин уже занят.'],
            ['username', 'string', 'min' => 2, 'max' => 255],*/

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Данная электронная почта уже зарегистрирована.'],

            ['password', 'required'],
            ['password', 'match', 'pattern' => '/^.*(?=.*\d)(?=.*[a-z,а-я,ё])(?=.*[A-Z,А-Я,Ё])(?=.*[@,_,-,\+,*,\/,\.,\,,\^,:,%]).*$/', 'message' => 'Пароль должен содержать минимум одну прописную букву, одну заглавную букву, одну цифру и один специальный символ'],
            ['password', 'string', 'min' => 12],

            ['passwordAgain', 'required'],
            ['passwordAgain', 'compare', 'compareAttribute'=>'password',  'message' => 'Пароли в двух полях должны совпадать'],
            ['passwordAgain', 'string', 'min' => 12],

            /*['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['surname', 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 255],

            ['middlename', 'trim'],*/
        ];
    }

    public function attributeLabels()
    {
        return array(
            'username' => 'Электронная почта',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
            'passwordAgain' => 'Повторите пароль',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'middlename' => 'Отчество',
        );
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        if ($this->password != $this->passwordAgain) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = "";//$this->name;
        $user->surname = "";//$this->surname;
        $user->middlename = "";//$this->middlename;
        $user->id_role = 2;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->email_token = md5($user->email.time());
        $user->sendConfirmLetter();
        
        //return $user->save() ? $user : null;
    }
}
