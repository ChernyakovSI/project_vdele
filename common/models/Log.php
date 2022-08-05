<?php


namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\User;

class Log extends ActiveRecord
{
    public static function tableName()
    {
        return '{{log}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'is_deleted', 'id_user'], 'integer'],
            [['url', 'status', 'description'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id_user' => 'Пользователь',
            'url' => 'url',
            'status' => 'Статус',
            'description' => 'Описание',
        );
    }

    public static function addRecord($params) {
        $newRec = new Log();

        $newRec->created_at = time();
        $newRec->id_user = (integer)$params['id_user'];
        $newRec->updated_at = time();

        $newRec->url = strip_tags($params['url']);
        $newRec->status = strip_tags($params['status']);
        $newRec->description = strip_tags($params['description']);

        $newRec->save();

        return $newRec;
    }

    public static function getLogs($beginDate = 0, $endDate = 0, $option = []){
        $query = new Query();
        $body = $query->Select(['Log.`id` as id',
                                            'Log.`created_at` as created_at',
                                            'Log.`id_user` as id_user',
                                            'Log.`url` as url',
                                            'Log.`status` as status',
                                            'Log.`description` as description',
                                            'IFNULL(usr.`surname`, "") as surname',
                                            'IFNULL(usr.`name`, "") as name',
                                            'IFNULL(usr.`middlename`, "") as middlename',
                                            'IFNULL(usr.`email`, "") as email',
                                            'IFNULL(usr.`username`, "") as username',
                                            'IFNULL(usr.`username`, "") as user'
        ])
            ->from(self::tableName().' as Log')
            //++ 1-2-3-007 05/08/2022
            //*-
            //->join('INNER JOIN', User::tableName().' as usr', 'usr.`id` = Log.`id_user`')
            //*+
            ->join('LEFT JOIN', User::tableName().' as usr', 'usr.`id` = Log.`id_user`')
            ->limit(30)
            //-- 1-2-3-007 05/08/2022

            ->where(['Log.`is_deleted`' => 0]);

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Log.`created_at` >= '.$beginDate)
                ->andWhere('Log.`created_at` <= '.$endDate);
        }

        if(isset($option['user'])){
            $body = $body->andWhere('Log.`id_user` = '.$option['user']);
        }
        if(isset($option['status'])){
            $body = $body->andWhere('Log.`status` = "'.$option['status'].'"');
        }
        //++ 1-2-3-007 05/08/2022
        if(isset($option['URL'])){
            $body = $body->andWhere('Log.`url` LIKE "%'.$option['URL'].'%"');
        }
        //-- 1-2-3-007 05/08/2022

        $result = $body->orderBy('Log.`created_at` DESC')->all();

        return $result;
    }

    public static function getUsers($beginDate = 0, $endDate = 0){
        $query = new Query();
        $body = $query->Select([
            'Log.`id_user` as id_user',
        ])->distinct()
            ->from(self::tableName().' as Log')

            ->where(['Log.`is_deleted`' => 0]);

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Log.`created_at` >= '.$beginDate)
                ->andWhere('Log.`created_at` <= '.$endDate);
        }

        $result = $body->orderBy('Log.`id_user` DESC')->all();

        return $result;
    }

    public static function getStatuses($beginDate = 0, $endDate = 0){
        $query = new Query();
        $body = $query->Select([
            'Log.`status` as name',
        ])->distinct()
            ->from(self::tableName().' as Log')

            ->where(['Log.`is_deleted`' => 0]);

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Log.`created_at` >= '.$beginDate)
                ->andWhere('Log.`created_at` <= '.$endDate);
        }

        $result = $body->orderBy('Log.`status` DESC')->all();

        return $result;
    }

    //++ 1-2-3-007 05/08/2022
    public static function getURLs($beginDate = 0, $endDate = 0){
        $query = new Query();
        $body = $query->Select([
            'Log.`url` as URL',
        ])->distinct()
            ->from(self::tableName().' as Log')

            ->where(['Log.`is_deleted`' => 0]);

        if ($beginDate > 0 || $endDate > 0){
            if ($beginDate > $endDate){
                $temp = $beginDate;
                $beginDate = $endDate;
                $endDate = $temp;
            }
            $body = $body->andWhere('Log.`created_at` >= '.$beginDate)
                ->andWhere('Log.`created_at` <= '.$endDate);
        }

        $result = $body->orderBy('Log.`URL`')->all();

        return $result;
    }
    //-- 1-2-3-007 05/08/2022

}