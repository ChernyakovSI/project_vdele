<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'name', 'surname', 'middlename'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'username' => 'Электронная почта для входа',
            'email' => 'Электронная почта для связи',
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
            'city' => 'Текущий город',
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
}
