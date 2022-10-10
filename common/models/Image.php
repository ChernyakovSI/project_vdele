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
use common\models\User;

//album 1 - фото в альбоме профиля
//album 2 - карточки запоминания

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

        //++ 1-2-4-001 31/08/2022
        return $image->id;
        //-- 1-2-4-001 31/08/2022
    }

    public function getPathAvatarForUser($id_user)
    {
        return User::getAvatarName($id_user);

        /*
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
        }*/
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

        //++ 1-2-4-001 31/08/2022
        $id = 0;
        //-- 1-2-4-001 31/08/2022

        if(isset($this->imageFile)) {
            $extension = $this->getExtension($this->imageFile['name']);
            $src = ''.$id_user.'_'.$id_album.'_'.$num. '.' . $extension;

            $pathName = $this->getPathForAlbum($id_album);

            $pathData = Yii::$app->params['dataUrl'];

            $fullPath = $pathData.'img/'.$pathName.$src;

            if (move_uploaded_file($this->imageFile['tmp_name'], $fullPath)) {
                // Далее можно сохранить название файла в БД и т.п.

                //++ 1-2-4-001 31/08/2022
                //!-
                //$this->addImage($id_user, $id_album, $num, $src);
                //!+
                $id = $this->addImage($id_user, $id_album, $num, $src);
                //-- 1-2-4-001 31/08/2022
            }
        }
        else
        {
            $fullPath = '';
        }

        //++ 1-2-4-001 31/08/2022
        //!-
        //return [$fullPath, $this->imageFile['tmp_name']];
        //!+
        return [$fullPath, $this->imageFile['tmp_name'], $id];
        //-- 1-2-4-001 31/08/2022

    }

    public function findImageBySrc($src, $id_album = 1, $fullPath = '') {
        $pathName = $this->getPathForAlbum($id_album);
        if($fullPath === '') {
            $fullPath = Yii::$app->params['dataUrl'].'img/'.$pathName;
        }

        $src = str_ireplace($fullPath, '', $src);

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
            ->where(['Img.`src`' => $src]);

        $result = $body->one();

        return $result;
    }

    public static function getImageIdBySrc($src, $id_album = 0) {
        $query = new Query();
        $body = $query->Select('Img.`id` as id')
            ->from(self::tableName().' as Img')
            ->where(['Img.`src`' => $src, 'Img.`is_deleted`' => 0]);

        $result = $body->one();

        if(isset($result)){
            if(isset($result['id'])){
                return $result['id'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }

    }

    public function replaceFileToDeleted($id, $id_album = 1) {

        $id = (int)$id;
        if($id === 0){
            return false;
        }

        $image = Image::find()->where(['id' => $id])->one();

        $pathName = $this->getPathForAlbum($id_album);

        $pathData = Yii::$app->params['dataUrl'];

        $fullPath = $pathData.'img/'.$pathName.$image->src;
        $fullPathDeleted = $pathData.'img/deleted/'.$pathName.$image->src;

        if (rename($fullPath, $fullPathDeleted)) {
            // Далее можно сохранить название файла в БД и т.п.
            return $this->deleteImage($id);
        }
        else{
            return [$fullPath, $fullPathDeleted];
        }

    }

    public function deleteImage($id) {

        if($id === 0){
            return false;
        }

        $image = Image::find()->where(['id' => $id])->one();
        $image->is_deleted = 1;
        $image->updated_at = time();
        $image->save();

        return true;
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

    public static function getFileName($fullName, $separator = '/') {
        $arrPath = explode($separator, $fullName);
        $lenPath = count($arrPath);
        if ($lenPath > 0) {
            return $arrPath[$lenPath-1];
        }
        else {
            return '';
        }
    }

    public function getPathForAlbum($id_album) {
        if ($id_album === 0) {
            return 'avatar/';
        }
        elseif ($id_album === 1) {
            return 'main/';
        }
        //++ 1-2-4-001 31/08/2022
        elseif ($id_album === 2) {
            return 'cards/';
        }
        //-- 1-2-4-001 31/08/2022
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

    public function hasFotos($id_user, $id_album = 0)
    {
        $num = Image::find()->select('max(num)')->where(['id_user' => $id_user, 'id_album' => $id_album, 'is_deleted' => 0])->scalar();

        if (isset($num)) {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}