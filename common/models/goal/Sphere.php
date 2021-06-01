<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 25.05.2021
 * Time: 9:47
 */

namespace common\models\goal;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Query;

class Sphere extends ActiveRecord
{
    public static function tableName()
    {
        return '{{sphere}}';
    }

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Название'
        );
    }

    public static function getAllBaseSpheres(){
        return self::find()->orderBy('id')->all();
    }

    public static function getAllSpheresByUser($id){
        $query = new Query();
        $body = $query->Select(['sphere.`id` as id',
            'ISNULL(sphereUser.`name`, sphere.`name`) as name'
        ])
            ->from(self::tableName().' as sphere');

        $body = $body->join('LEFT JOIN', 'sphere_user as sphereUser', 'sphereUser.`id_sphere` = sphere.`id` AND sphereUser.`id_user` = '.$id);

        return $body->all();
    }

    public static function getSpheresByUser($id_user){
        $query = new Query();
        $body = $query->Select(['Sphere.`id` as id',
            'IFNULL(sphereUser.`name`, Sphere.`name`) as name'
        ])
            ->from(self::tableName().' as Sphere');

        $strWhere = 'sphereUser.`id_sphere`= Sphere.`id`';
        $strWhere = $strWhere.' AND sphereUser.`id_user` = '.(integer)$id_user;

        $body = $body->join('LEFT JOIN', 'sphere_user as sphereUser', $strWhere);
        $body = $body->orderBy('id');

        return $body->all();
    }

    public static function getSphereByUserAndSphere($id_user, $id_sphere){
        $query = new Query();
        $body = $query->Select(['Sphere.`id` as id',
            'IFNULL(sphereUser.`name`, Sphere.`name`) as name'
        ])
            ->from(self::tableName().' as Sphere');

        $strWhere = 'sphereUser.`id_sphere`= Sphere.`id`';
        $strWhere = $strWhere.' AND sphereUser.`id_user` = '.(integer)$id_user;

        $body = $body->join('LEFT JOIN', 'sphere_user as sphereUser', $strWhere);

        $arrWhere = [];
        $arrWhere['Sphere.`id`'] = (integer)$id_sphere;

        $body = $body->where($arrWhere);

        return $body->all();
    }

    public static function getColorForId($id, $mode = 0, $withHover = 0){
        $color = '';

        if ($id === 1) {
           if ($mode === 0){
               $color = 'col-back-inn-light';
           }
           else {
               $color = 'col-back-inn';
           }
        }
        else if ($id === 2) {
            if ($mode === 0){
                $color = 'col-back-fin-light';
            }
            else {
                $color = 'col-back-fin';
            }
        }
        else if ($id === 3) {
            if ($mode === 0){
                $color = 'col-back-hea-light';
            }
            else {
                $color = 'col-back-hea';
            }
        }
        else if ($id === 4) {
            if ($mode === 0){
                $color = 'col-back-rel-light';
            }
            else {
                $color = 'col-back-rel';
            }
        }
        else if ($id === 5) {
            if ($mode === 0){
                $color = 'col-back-edu-light';
            }
            else {
                $color = 'col-back-edu';
            }
        }
        else if ($id === 6) {
            if ($mode === 0){
                $color = 'col-back-rea-light';
            }
            else {
                $color = 'col-back-rea';
            }
        }
        else if ($id === 7) {
            if ($mode === 0){
                $color = 'col-back-psy-light';
            }
            else {
                $color = 'col-back-psy';
            }
        }
        else if ($id === 8) {
            if ($mode === 0){
                $color = 'col-back-tra-light';
            }
            else {
                $color = 'col-back-tra';
            }
        }

        if ($withHover == 1){
            $colorHov = $color.'-harder';
            $color = $color.' '.$colorHov;
        }

        return $color;
    }
}