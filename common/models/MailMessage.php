<?php


namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\MailUser;
use common\models\Settings;

class MailMessage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{mail_message}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user'], 'integer'],
            [['title', 'text'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id_user' => 'Пользователь',
            'name' => 'Заголовок',
            'value' => 'Текст',
        );
    }

    public static function addRecord($data, $id_user) {
        $newRec = new MailMessage();

        $newRec->created_at = time();
        $newRec->id_user = $id_user;
        $newRec->updated_at = time();

        $newRec->title = $data['title'];
        $newRec->text = $data['text'];

        $newRec->save();

        $rec = SELF::initSender($newRec['id']);

        return $newRec;
    }

    public static function initSender($id_mail) {
        $AllUsers = Settings::getUsersWithSet('b_gen_SubscribeNews', 1);

        foreach ($AllUsers as $data) {
            $rec = MailUser::addRecord($id_mail, $data['id_user']);
        }

        return $rec;
    }

}