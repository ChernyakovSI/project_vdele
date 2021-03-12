<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 23.06.2019
 * Time: 17:52
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use Yii;

class Image extends ActiveRecord
{
    public $imageFile;

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
            [['imageFile'], 'file', 'extensions' => 'png, jpg'],
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

    public function getPathOfLastPicture($id_user, $id_album = 0)
    {
        $num = Image::find()->select('max(num)')->where(['id_user' => $id_user, 'id_album' => $id_album])->scalar();
        $num = (int)$num;

        $src = Image::find()->select('max(src)')->where(['id_user' => $id_user, 'id_album' => $id_album, 'num' => $num])->scalar();

        if (isset($src)) {
            return $src;
        }
        else
        {
            return '';
        }
    }

    public function addImage($id_user, $id_album, $num, $src, $description = '') {

        if(($src == '') && ($this->getPathAvatarForUser($id_user) == '')){
            return;
        }

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
            $path = Image::find()->select('max(src)')->where(['id_user' => $id_user, 'id_album' => 0, 'num' => $num])->scalar();
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

    public static function getAllImagePathsForUserAndAlbum($id_user, $id_album)
    {
        $query = new Query();
        $body = $query->Select('Img.`id` as id,
                                            Img.`description` as description,
                                            Img.`id_album` as id_album,
                                            Img.`created_at` as created_at,
                                            Img.`updated_at` as updated_at,
                                            Img.`num` as num,
                                            Img.`id_user` as id_user,
                                            Img.`src` as src
                                            ')
            ->from(self::tableName().' as Img')
            ->where(['Img.`id_user`' => $id_user, 'Img.`id_album`' => $id_album, 'Img.`is_deleted`' => 0]);

        $result = $body->orderBy('Img.`created_at` DESC')->all();

        return $result;
    }

    public function upload($file, $id_album = 1)
    {
        $this->imageFile = $file;

        $id_user = Yii::$app->user->identity->getId();
        $num = $this->getNextNumForUser($id_user, $id_album);

        if(isset($this->imageFile)) {
            $extension = $this->getExtension($this->imageFile['name']);
            $src = ''.$id_user.'_'.$id_album.'_'.$num. '.' . $extension;

            $pathName = $this->getPathForAlbum($id_album);

            $pathData = Yii::$app->params['dataUrl'];

            $fullPath = $pathData.'img/'.$pathName.$src;

            //if (move_uploaded_file($this->imageFile['tmp_name'], $fullPath)) {
                // Далее можно сохранить название файла в БД и т.п.
            //    $this->addImage($id_user, $id_album, $num, $src);
            //}
        }
        else
        {
            $fullPath = '';
        }

        return [$fullPath, $this->imageFile['tmp_name']];

    }

    public function getExtension($filename) {
        $arrPath = explode(".", $filename);
        $lenPath = count($arrPath);
        if ($lenPath > 0) {
            return $arrPath[$lenPath-1];
        }
        else {
            return '';
        }

        //return end(explode(".", $filename));
        //return pathinfo($filename);
    }

    public function getPathForAlbum($id_album) {
        if ($id_album === 0) {
            return 'avatar/';
        }
        elseif ($id_album === 1) {
            return 'main/';
        }
        else {
            return 'error/';
        }
    }


    //Не используется
    public static function getImageURL($name, $id_album){
        $image = new Image();
        $pathName = $image->getPathForAlbum($id_album);
        $file = file_get_contents(Yii::$app->params['doman'].Yii::$app->params['dataUrl'].'img/'.$pathName.$name, true);
        return $file;
    }
}