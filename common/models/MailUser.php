<?php


namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\MailMessage;

class MailUser extends ActiveRecord
{
    public static function tableName()
    {
        return '{{mail_user}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user', 'id_mail', 'status'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id_user' => 'Получатель',
            'id_mail' => 'Письмо',
            'status' => 'Отправлено',
        );
    }

    public static function getUsersForMail() {
        $query = new Query();
        $body = $query->Select(['mu.`id` as id',
            'mu.`id_user` as id_user',
            'mm.`title` as title',
            'mm.`text` as text',
        ])
            ->from(self::tableName().' as mu')
            ->join('LEFT JOIN', MailMessage::tableName().' as mm', 'mu.`id_mail` = mm.`id`');

        $strWhere = 'mu.`status`= 0';
        $strWhere = $strWhere.' AND mm.`is_deleted` = 0 ';
        $strWhere = $strWhere.' AND mu.`is_deleted` = 0 ';

        $result = $body->where($strWhere)->orderBy('mu.`id`')->all();

        return $result;
    }

    public static function addRecord($id_mail, $id_user) {
        $newRec = new MailUser();

        $newRec->created_at = time();
        $newRec->id_user = (integer)$id_user;
        $newRec->updated_at = time();
        $newRec->is_deleted = 0;

        $newRec->id_mail = (integer)$id_mail;
        $newRec->status = 0;

        $newRec->save();

        return $newRec;
    }

    public function setSended($sended = 1) {
        $this->status = $sended;
        $this->save();

        return 1;
    }
}