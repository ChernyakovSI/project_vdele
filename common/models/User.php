<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;
use yii\helpers\Html;
use common\models\Image;
use DateTime;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $name
 * @property string $surname
 * @property string $middlename
 * @property integer $last_activity
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_GUEST = 1;
    const ROLE_USER = 2;
    const ROLE_ADMIN = 3;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $id_team;
    public $id_user;

    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $pathImageFile;

    //public $id_role;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['id', 'status', 'id_role', 'created_at', 'updated_at', 'gender'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'name', 'surname', 'middlename',
                'phone', 'url_vk', 'url_fb', 'url_ok', 'url_in', 'url_www', 'skype', 'icq', 'about'], 'safe'],
            [['imageFile'], 'file', 'extensions' => 'png, jpg'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'username' => 'Логин',
            'email' => 'Email',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'middlename' => 'Отчество',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'id_role' => 'Роль',
            'userFIO' => 'Участник',
            'gender' => 'Пол',
            'date_of_birth' => 'Дата рождения',
            'date_of_birth_str' => 'Дата рождения',
            'city' => 'Город',

            'phone' => 'Телефон',
            'url_vk' => 'ВКонтакте',
            'url_fb' => 'Facebook',
            'url_ok' => 'Одноклассники',
            'url_in' => 'Instagram',
            'url_www' => 'Другой сайт',
            'skype' => 'Skype',
            'icq' => 'ICQ',

            'about' => 'Дополнительная информация',

            'imageFile' => 'Фото профиля',
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /*public static function findUserAndRole($id)
    {
        if ((isset($id)) && ($id > 0)) {
            $columns_array = [
                'user.id as id',
                'user.username as username',
                'user.auth_key as auth_key',
                'user.password_hash as password_hash',
                'user.password_reset_token as password_reset_token',
                'user.email as email',
                'user.status as status',
                'user.id_role as id_role',
                'user.created_at as created_at',
                'user.updated_at as updated_at',
                'user.name as name',
                'user.surname as surname',
                'user.middlename as middlename',
                'role.name as role'
            ];

            $query = User::find()->select($columns_array);
            $query->innerJoin('role as role', 'user.id_role = role.id');
            $model = $query->where('user.id = '.$id)->one();

            return $model;

        }
    }*/

    public static function getUserFIO($id)
    {
        if ((isset($id)) && ($id > 0)) {
            $columns_array = [
                'user.surname as surname',
                'user.name as name',
                'user.middlename as middlename',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->where('user.id = '.$id)->one();

            if (isset($model->middlename)) {
                $userFIO = $model->surname." ".$model->name." ".$model->middlename;
            }
            else {
                $userFIO = $model->surname." ".$model->name;
            }


            return trim($userFIO);

        }
    }

    public static function getUserIO($id)
    {
        if ((isset($id)) && ($id > 0)) {
            $columns_array = [
                'user.name as name',
                'user.middlename as middlename',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->where('user.id = '.$id)->one();

            if (isset($model->middlename)) {
                $userIO = $model->name." ".$model->middlename;
            }
            else {
                $userIO = $model->name;
            }


            return trim($userIO);

        }
    }


    public function getFIO($user_id, $abbreviated = false)
    {
        if ((isset($user_id)) && ($user_id > 0)) {
            $columns_array = [
                'user.surname as surname',
                'user.name as name',
                'user.middlename as middlename',
                'user.email as email',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->where('user.id = '.$user_id)->one();

            $userFIO = '';
            if ((isset($model->surname)) && ($model->surname !== '')) {
                $userFIO = $userFIO." ".$model->surname;
            }
            $nameIsFull = " ";
            if ((isset($model->name)) && ($model->name !== '')) {
                if ($abbreviated == true)
                {
                    $nameIsFull = "";
                    $userFIO = $userFIO." ".mb_substr($model->name, 0, 1).".";
                }
                else
                    $userFIO = $userFIO." ".$model->name;
            }
            if ((isset($model->middlename)) && ($model->middlename !== '')) {
                if ($abbreviated == true)
                    $userFIO = $userFIO.$nameIsFull.mb_substr($model->middlename, 0, 1).".";
                else
                    $userFIO = $userFIO." ".$model->middlename;
            }

            if ($userFIO == "") {
                $userFIO = $model->email;
            }

            return trim($userFIO);

        }
        else
        {
            return '';
        }
    }

    public static function getI($user_id)
    {
        if ((isset($user_id)) && ($user_id > 0)) {
            $columns_array = [
                'user.name as name',
                'user.email as email',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->where('user.id = '.$user_id)->one();

            $userI = '';

            if ((isset($model->name)) && ($model->name !== '')) {
                $userI = $model->name;
            }

            if ($userI == "") {
                $userI = $model->email;
            }

            return trim($userI);

        }
        else
        {
            return '';
        }
    }

    public static function getFI($user_id, $abbreviated = false)
    {
        if ((isset($user_id)) && ($user_id > 0)) {
            $columns_array = [
                'user.surname as surname',
                'user.name as name',
                'user.email as email',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->where('user.id = '.$user_id)->one();

            $userFIO = '';
            if ((isset($model->surname)) && ($model->surname !== '')) {
                $userFIO = $userFIO." ".$model->surname;
            }
            if ((isset($model->name)) && ($model->name !== '')) {
                if ($abbreviated == true)
                {
                    $userFIO = $userFIO." ".mb_substr($model->name, 0, 1).".";
                }
                else
                    $userFIO = $userFIO." ".$model->name;
            }

            if ($userFIO == "") {
                $userFIO = $model->email;
            }

            return trim($userFIO);

        }
        else
        {
            return '';
        }
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getFullYears($birthdayDate) {
        $v  = time() - $birthdayDate;
        return date('Y',$v)-1970;
    }

    public function getInclinationByNumber($number, $arr = Array()) {
        $number = (string) $number;
        $numberEnd = substr($number, -2);
        $numberEnd2 = 0;
        if(strlen($numberEnd) == 2){
            $numberEnd2 = $numberEnd[0];
            $numberEnd = $numberEnd[1];
        }

        if ($numberEnd2 == 1) return $arr[2];
        else if ($numberEnd == 1) return $arr[0];
        else if ($numberEnd > 1 && $numberEnd < 5)return $arr[1];
        else return $arr[2];
    }

    public function upload($id_album = 0)
    {
        if ($this->validate()) {
            $id_user = Yii::$app->user->identity->getId();
            $imageFile = new Image();
            $num = $imageFile->getNextNumForUser($id_user, $id_album);

            if(isset($this->imageFile)) {
                $src = ''.$id_user.'_'.$id_album.'_'.$num. '.' . $this->imageFile->extension;
                $this->imageFile->saveAs('data/img/avatar/'.$src);

            }
            else
            {
                $src = '';
            }

            $imageFile->addImage($id_user, $id_album, $num, $src);

            return true;
        } else {
            return false;
        }
    }

    public function sendConfirmLetter()
    {
        $subject = "Подтверждение почты на сайте ".$_SERVER['HTTP_HOST'];

        //Устанавливаем кодировку заголовка письма и кодируем его
        $subject = "=?utf-8?B?".base64_encode($subject)."?=";

        //Составляем тело сообщения
        $message = 'Здравствуйте! <br/> <br/> Сегодня '.date("d.m.Y", time()).', кем-то была произведена 
            регистрация на сайте <a href="'.Yii::$app->params['doman'].'">'.$_SERVER['HTTP_HOST'].'</a> используя Ваш email. 
            Если это были Вы, то, пожалуйста, подтвердите адрес вашей электронной почты, перейдя по этой ссылке: 
            <a href="'.Yii::$app->params['doman'].'activation/?token='.$this->email_token.'&email='.$this->email.'">'.'
            Подтвердите регистрацию'.'</a> или скопируйте этот URL в адресную строку браузера: '
            .Yii::$app->params['doman'].'activation/?token='.$this->email_token.'&email='.$this->email.' 
            <br/> <br/> В противном случае, если это были не Вы, то, просто игнорируйте это письмо.';

        $message = 'Здравствуйте! <br/> <br/> Сегодня, '.date("d.m.Y", time()).', была произведена 
            регистрация на сайте <a href="'.Yii::$app->params['doman'].'">'.$_SERVER['HTTP_HOST'].'</a> используя Ваш email. 
            Если это были Вы, то, пожалуйста, подтвердите адрес вашей электронной почты, перейдя по этой ссылке: 
            <a href="'.Yii::$app->params['doman'].'activation/?token='.$this->email_token.'&email='.$this->email.'">'.'
            Подтвердите регистрацию'.'</a> 
            <br/> <br/> В противном случае, если это были не Вы, то, просто игнорируйте это письмо.';

        //Составляем дополнительные заголовки для почтового сервиса mail.ru
        //Переменная $email_admin, объявлена в файле dbconnect.php
        //$headers = 'FROM: '.Yii::$app->params['adminEmail'].'\r\nReply-to: '.Yii::$app->params['adminEmail'].'\r\nContent-type: text/html; charset=utf-8\r\n';

        //"From: robot@" . $_SERVER['HTTP_HOST']

        //Yii::$app->session->setFlash('success', 'Начало: '.$this->email);

        //$email = $_REQUEST['email'] ;
        $emailFrom = Yii::$app->params['robotEmail'];//"robot@yavdele.net";
        $emailTo = $this->email;
        /*mail( "$emailTo", "$subject",
            $message, "From: robot@" . $_SERVER['HTTP_HOST'] ."\r\n"
            ."Content-type: text/html; charset=utf-8\r\n"
            ."X-Mailer: PHP mail script" );*/

        //mail($this->email, $subject, $message, $headers);...


        Yii::$app->mailer->compose()
            ->setTo($emailTo)
            ->setFrom([$emailFrom => "Я в деле"])
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send();

        //Yii::$app->session->setFlash('success', 'Конец: '.$this->email);


    }

    public static function activate($token, $email)
    {
        $realToken = User::find()->select('max(email_token)')->where(['email' => $email])->scalar();

        if (isset($realToken) && ($realToken == $token)) {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function activated($email)
    {
        $realStatus = User::find()->select('max(email_status)')->where(['email' => $email])->scalar();

        if (isset($realStatus) && ($realStatus == 1)) {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function register_activity(){
        $this->last_activity = time();
        $this->save();

        return true;
    }

    public function getTimeLastActivity(){
        $lastActivity = $this->last_activity;

        if((time() - 60) < $lastActivity){
            return 'в сети';
        }

        $message = '';

        $start_day = strtotime('now 00:00:00');
        if($start_day < $lastActivity) {
            return $message.date('H:i', $lastActivity);
        }
        elseif (($start_day - 24*60*60) < $lastActivity) {
            return $message.'вчера '.date('H:i', $lastActivity);
        }

        $month = date('n', $lastActivity);
        $year = date('Y', $lastActivity);
        $yearNow = date('Y');
        $months = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

        $yearD = DateTime::createFromFormat('d.m.Y', '1.1.'.$year);
        $yearNowD = DateTime::createFromFormat('d.m.Y', '1.1.'.$yearNow);

        if($yearD == $yearNowD) {
            return $message.date('j '.$months[$month-1], $lastActivity);
        }
        else{
            return $message.date('j '.$months[$month-1].' Y', $lastActivity);
        }


    }

    public static function wrapURL($url, $insreadAt = ''){

        if($insreadAt != '' && stripos($url, '@') === 0){
            $url = mb_substr($url, 1);
            $url = $insreadAt.$url;
        }

        if(stripos($url, 'http://') === false && stripos($url, 'https://') === false){
            $url = 'http://'.$url;
        }

        return $url;
    }
}


