<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "role".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 * @property int $updated_at
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Роль',
            'created_at' => 'Создана',
            'updated_at' => 'Изменена',
        ];
    }

    public static function getArrayOfRoles(){
        $roles = Role::find()->all();
        $items = ArrayHelper::map($roles,'id','name');

        return $items;
    }

    public static function getRoleById($id){
        $role = Role::findOne($id);

        if (isset($role)) {
            return $role->name;
        }
        else {
            return '';
        }

    }
}
