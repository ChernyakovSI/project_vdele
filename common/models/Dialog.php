<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 19.07.2020
 * Time: 22:54
 */

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\DialogUsers;
use common\models\User;

class Dialog extends ActiveRecord
{
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dialog}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'src_image'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return array(
            'name' => 'Название',
            'src_image' => 'Аватарка',
        );
    }

    public static function getDialogByUser($user_id, $user_id2, $create_new = 0)
    {
        $Dialogs1 = DialogUsers::find()->select('id, id_dialog')->where(['id_user' => $user_id])->all();

        if (count($Dialogs1) == 0){
            if ($create_new == 0){
                return null;
            }
            else{
               return Dialog::createNewDialog($user_id, $user_id2);
            }
        }

        $Dialogs2 = DialogUsers::find()->select('id, id_dialog')->where(['id_user' => $user_id2])->all();

        if (count($Dialogs2) == 0){
            if ($create_new == 0){
                return null;
            }
            else{
                return Dialog::createNewDialog($user_id, $user_id2);
            }
        }

        $arrDialogs = [];
        foreach ($Dialogs1 as $dialog1){
            foreach($Dialogs2 as $dialog2) {
                if ($dialog1->id_dialog == $dialog2->id_dialog){
                    $arrDialogs[] = $dialog1->id_dialog;
                }
            }
        }

        $DialogsForTwo = DialogUsers::find()->select('id_dialog, count(id_user)')->where(['id_dialog' => $arrDialogs])->
            groupBy('id_dialog')->having(['count(id_user)' => 2])->all();

        if(count($DialogsForTwo) > 0){
            return $DialogsForTwo[0]['id_dialog'];
        }

        return null;
    }

    public static function createNewDialog($user_id, $user_id2) {
       $dialog = new Dialog();
       $dialog->save();

       DialogUsers::addUserToDialog($user_id, $dialog->id);
       DialogUsers::addUserToDialog($user_id2, $dialog->id);

       return $dialog->id;
    }

    public static function getNameById($id){
        return Dialog::find()->select('name')->where(['id' => $id])->one()->name;
    }

    public function getId(){
        return $this->id;
    }
}