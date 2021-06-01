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
use yii\db\Query;
use common\models\Tag;

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
                'phone', 'url_vk', 'url_fb', 'url_ok', 'url_in', 'url_www', 'skype', 'icq', 'about', 'path_avatar', 'telegram'], 'safe'],
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

            'path_avatar' => 'Имя аватарки',
            'telegram' => 'Телеграм',
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

    public static function getFIO_s($user_id, $abbreviated = false)
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

    public static function getAvatarName($user_id)
    {
        $query = new Query();
        $body = $query->Select('Img.`src` as src,
                                        User.`gender` as gender')
            ->from(self::tableName().' as User')

            ->join('LEFT JOIN', Image::tableName().' as Img', 'User.`image_id` = Img.`id` AND Img.`is_deleted` = 0')

            ->where(['User.`id`' => $user_id]);

        $result = $body->all();

        if(isset($result) && count($result) > 0) {
            if(isset($result[0]['src']) && $result[0]['src'] !== ''){
                return $result[0]['src'];
            }
            else {
                if($result[0]['gender'] == 2) {
                    return 'avatar_default_w.jpg';
                }
                else{
                    return 'avatar_default.jpg';
                }
            }
        }
        else {
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
        $message = 'Здравствуйте! <br/> <br/> Сегодня, '.date("d.m.Y", time()).', была произведена 
            регистрация на сайте <a href="'.Yii::$app->params['doman'].'">'.$_SERVER['HTTP_HOST'].'</a> используя Ваш email. 
            Если это были Вы, то, пожалуйста, подтвердите адрес вашей электронной почты, перейдя по этой ссылке: 
            <a href="'.Yii::$app->params['doman'].'activation/?token='.$this->email_token.'&email='.$this->email.'">'.'
            Подтвердите регистрацию'.'</a> или скопируйте этот URL в адресную строку браузера: '
            .Yii::$app->params['doman'].'activation/?token='.$this->email_token.'&email='.$this->email.' 
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

    public static function getTimeLastActivity_s($id){
        $thisUser = User::find()->where(['id' => (int)$id])->one();
        if (isset($thisUser)) {
            return $thisUser->getTimeLastActivity();
        }
        else {
            return '';
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

    public static function edit($data, $id){
        $User = static::findOne(['id' => $id]);

        $User->surname = strip_tags($data['surname']);//(integer)$data['id_type'];
        $User->name = strip_tags($data['name']);
        $User->middlename = strip_tags($data['middlename']);

        $User->gender = (integer)$data['gender'];

        $User->date_of_birth = (integer)$data['date_of_birth'];//->getTimestamp();

        $User->id_city = (integer)$data['id_city'];
        $User->email = strip_tags($data['email']);
        $User->phone = strip_tags($data['phone']);

        $User->url_vk = strip_tags($data['url_vk']);
        $User->url_fb = strip_tags($data['url_fb']);
        $User->url_ok = strip_tags($data['url_ok']);
        $User->url_in = strip_tags($data['url_in']);
        $User->url_www = strip_tags($data['url_www']);

        $User->telegram = strip_tags($data['telegram']);
        $User->skype = strip_tags($data['skype']);
        $User->icq = strip_tags($data['icq']);

        $User->about = strip_tags($data['about']);

        $src = Image::getFileName($data['avatarName']);
        $image_id = Image::getImageIdBySrc($src);
        $User->image_id = $image_id;

        if(isset($data['updated_at'])) {
            $User->updated_at = (integer)$data['updated_at'];
        }
        else{
            $User->updated_at = time();
        };

        //return $User;

        $User->save();

        Tag::editTagsForUser($User->id, $data['tags']);

        return true;
    }

    public static function findWithParams($params) {
        $fullYears = '(YEAR(CURRENT_DATE) - YEAR(FROM_UNIXTIME(User.`date_of_birth`))) - (DATE_FORMAT(CURRENT_DATE, "%m%d") < DATE_FORMAT(FROM_UNIXTIME(User.`date_of_birth`), "%m%d"))';

        $query = new Query();
        $body = $query->Select(['User.`id` as id',
            'User.`email` as email',
            'User.`status` as status',
            'User.`id_role` as id_role',
            'User.`created_at` as created_at',
            'User.`updated_at` as updated_at',
            'User.`name` as name',
            'User.`surname` as surname',
            'User.`middlename` as middlename',
            'User.`gender` as gender',
            'User.`date_of_birth` as date_of_birth',
            $fullYears.' as fullYears',
            'User.`id_city` as id_city',
            'User.`phone` as phone',
            'User.`url_vk` as url_vk',
            'User.`url_fb` as url_fb',
            'User.`url_ok` as url_ok',
            'User.`url_in` as url_in',
            'User.`url_www` as url_www',
            'User.`skype` as skype',
            'User.`icq` as icq',
            'User.`about` as about',
            'User.`last_activity` as last_activity',
            'User.`telegram` as telegram',
            'User.`image_id` as image_id'
        ])
            ->from(self::tableName().' as User');

        $arrWhere = [];
        $strWhere = '';
        $strWhereCon = '';

        if($params->get('tag')) {
            $tagName = $params->get('tag');

            $body = $body->join('INNER JOIN', 'tag_user as TagUser', 'TagUser.`id_user` = User.`id`')
                ->join('INNER JOIN', 'tag_item as TagItem', 'TagUser.`id_tag` = TagItem.`id`');
                //->where(['TagItem.`name`' => $tagName]);

            $arrWhere['TagItem.`name`'] = $tagName;
        }

        if($params->get('gen')) {
            $genName = $params->get('gen');

            $arrWhere['User.`gender`'] = (integer)$genName;
        }

        if($params->get('city')) {
            $cityName = $params->get('city');

            $body = $body->join('INNER JOIN', 'city as City', 'City.`id` = User.`id_city`');

            $arrWhere['City.`name`'] = $cityName;
        }

        if($params->get('fio')) {
            $fio = $params->get('fio');
            $arrFio = explode(',', $fio);

            $strFio = implode('", "',$arrFio);

            $strWhere = $strWhere.$strWhereCon.'User.`name` IN ("'.$strFio.'") OR User.`surname` IN ("'.$strFio.'") OR User.`middlename` IN ("'.$strFio.'")';
            $strWhereCon = ' AND ';
        }

        $isAge = false;
        if($params->get('af')) {
            $ageFrom = $params->get('af');

            $strWhere = $strWhere.$strWhereCon.$fullYears.' >= '.$ageFrom;
            $strWhereCon = ' AND ';
            $isAge = true;
        }

        if($params->get('at')) {
            $ageTo = $params->get('at');

            $strWhere = $strWhere.$strWhereCon.$fullYears.' <= '.$ageTo;
            $strWhereCon = ' AND ';
            $isAge = true;
        }

        if($isAge === true) {
            $strWhere = $strWhere.$strWhereCon.' User.`date_of_birth` != 0 ';
            $strWhereCon = ' AND ';
            $isAge = true;
        }

        if (count($arrWhere) > 0) {
            $body = $body->where($arrWhere);
            if ($strWhere !== '') {
                $body = $body->andWhere($strWhere);
            }
        }
        elseif ($strWhere !== '') {
            $body = $body->where($strWhere);
        }

        return $body;
    }
}


