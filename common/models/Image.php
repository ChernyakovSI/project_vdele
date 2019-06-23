<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 23.06.2019
 * Time: 17:52
 */

namespace common\models;

use yii\db\ActiveRecord;

class Image extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_album', 'created_at', 'updated_at', 'num', 'id_user'], 'integer'],
            [['src', 'description'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'description' => 'Описание',
        );
    }

    /**
     * @return mixed
     */
    public function getNextNumForUser($id_user, $id_album = 0)
    {
        //$sql = 'SELECT MAX(num) FROM image WHERE id_user=:id_user AND id_album=:id_album';
        //$sql = 'SELECT MAX(num) AS maxnum FROM che_goal.image';
        //$num = Image::findBySql($sql)->all();
        $num = Image::find()->select('max(num)')->where(['id_user' => $id_user, 'id_album' => $id_album])->scalar();
        $num = (int)$num;

        if (isset($num)) {
            return ($num + 1);
        }
        else
        {
            return 1;
        }
    }

    public function addImage($id_user, $id_album, $num, $src, $description = '') {
        $image = new Image();
        $image->src = $src;
        $image->description = $description;
        $image->id_album = $id_album;
        $image->num = $num;
        $image->id_user = $id_user;
        $image->created_at = time();
        $image->updated_at = time();
        $image->save();
    }

    public function getPathAvatarForUser($id_user)
    {
        $num = Image::find()->select('max(num)')->where(['id_user' => $id_user, 'id_album' => 0])->scalar();
        $num = (int)$num;

        if (isset($num)) {
            $path = Image::find()->select('src')->where(['id_user' => $id_user, 'id_album' => 0, 'num' => $num])->scalar();
            if (isset($path)) {
                return $path;
            }
            else
            {
                return '';
            }
        }
        else
        {
            return '';
        }
    }
}