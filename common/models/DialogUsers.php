<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 19.07.2020
 * Time: 22:54
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class DialogUsers extends ActiveRecord
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dialog_users}}';
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
            [['id', 'id_dialog', 'id_user'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id_dialog' => 'Диалог',
            'id_user' => 'Пользователь',
        );
    }

    public static function addUserToDialog($user_id, $dialog_id) {
        $dialogUser = new DialogUsers;
        $dialogUser->id_user = $user_id;
        $dialogUser->id_dialog = $dialog_id;
        $dialogUser->save();

        return $dialogUser->id;
    }

}