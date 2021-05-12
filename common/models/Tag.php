<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 28.04.2021
 * Time: 9:26
 */

namespace common\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use common\models\TagUser;

class Tag extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%tag_item}}';
    }

    public function attributeLabels()
    {
        return array(
            'is_deleted' => 'Удален',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'id_owner' => 'Создатель',
            'name' => 'Название'
        );
    }

    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }

    public static function findOrCreate($id, $name, $id_user) {
        $cur_tag = static::findById($id);
        if (isset($cur_tag)) {
            return $cur_tag;
        }

        $cur_tag = static::findByName($name);
        if (isset($cur_tag)) {
            return $cur_tag;
        }
        else {
            $cur_tag = new Tag();
            $cur_tag->name = $name;
            $cur_tag->id_owner = $id_user;
            $cur_tag->created_at = time();
            $cur_tag->updated_at = time();
            $cur_tag->save();
            return $cur_tag;
        }
    }

    public static function getTagsByUser($id_user){
        $query = new Query();
        $body = $query->Select(['Tag.`id` as id',
                                'Tag.`name` as name',
                                'Tag.`id` % 8 as color'
        ])
            ->from(self::tableName().' as Tag')
            ->join('INNER JOIN', 'tag_user as TagUser', 'TagUser.`id_tag` = Tag.`id`')
            ->where(['TagUser.`id_user`' => $id_user]);

        $result = $body->all();

        return $result;
    }

    public static function getAllTags(){
        $query = new Query();
        $body = $query->Select(['Tag.`id` as id',
            'Tag.`name` as name',
            'count_users' =>
                (new Query())->Select('count(*)')
                    ->from('tag_user as TagUserAll')

                    ->where('Tag.`id` = TagUserAll.`id_tag`')
        ])
            ->from(self::tableName().' as Tag')
            ->join('INNER JOIN', 'tag_user as TagUser', 'TagUser.`id_tag` = Tag.`id`')
            ->distinct();

        $result = $body->orderBy('count_users DESC')->all();

        return $result;
    }

    public static function editTagsForUser($id_user, $tags) {
        $tagsBefore = static::getTagsByUser($id_user);
        foreach ($tags as $tag) {
            if ($tag['name'] !== '' && $tag['id'] !== '') {
                $wasFound = false;
                foreach ($tagsBefore as $tagBefore) {
                    if ($tag['id'] == $tagBefore['id']) {
                        // Уже есть
                        $wasFound = true;
                        break;
                    }
                }
                if ($wasFound === false){
                    $newTag = static::findOrCreate($tag['id'], $tag['name'], $id_user);
                    $tag['id'] = $newTag->id;

                    $newTagUser = TagUser::addRecord($id_user, $newTag->id);
                }
            }
        }
        foreach ($tagsBefore as $tagBefore) {

            $wasFound = false;
            foreach ($tags as $tag) {
                if ($tag['id'] == $tagBefore['id']) {
                    $wasFound = true;
                    break;
                }
            }
            if($wasFound === false){
                TagUser::deleteRecord($id_user, $tagBefore['id']);
            }
        }
    }
}
