<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 01.06.2021
 * Time: 8:57
 */

namespace common\models\goal;

use yii\db\ActiveRecord;
use yii\db\Query;

class SphereUser extends ActiveRecord
{

    public static function tableName()
    {
        return '{{sphere_user}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'id_user', 'id_sphere'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Название',
            'id_user' => 'Пользователь',
            'id_sphere' => 'Сфера жизни'
        );
    }

    public static function editRecord($id_user, $id_sphere, $name) {
        $rec = SphereUser::getRecordIdByUserAndSphere($id_user, $id_sphere);

        if (count($rec) > 0){
            $newRec = static::findOne(['id' => $rec[0]['id']]);
        }
        else{
            $newRec = new SphereUser();
            $newRec->created_at = time();
        }

        $newRec->id_user = $id_user;
        $newRec->id_sphere = $id_sphere;
        $newRec->updated_at = time();
        $newRec->name = strip_tags($name);
        $newRec->save();

        return $newRec;
    }

    public static function getRecordIdByUserAndSphere($id_user, $id_sphere){
        $query = new Query();
        $body = $query->Select(['SphereUser.`id` as id'
        ])
            ->from(self::tableName().' as SphereUser');

        $arrWhere = [];
        $arrWhere['sphereUser.`id_sphere`'] = (integer)$id_sphere;
        $arrWhere['sphereUser.`id_user`'] = (integer)$id_user;

        $body = $body->where($arrWhere);

        return $body->all();
    }

}