<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 06.05.2019
 * Time: 22:39
 */

namespace common\models;

class Ac
{
    public function getFIO($user_id, $abbreviated = false)
    {
        if ((isset($user_id)) && ($user_id > 0)) {
            $columns_array = [
                'user.surname as surname',
                'user.name as name',
                'user.middlename as middlename',
                'user.email as email',
            ];

            $query = User::find()->select($columns_array);
            $model = $query->where('user.id = '.$user_id)->one();

            $userFIO = '';
            if ((isset($model->surname)) && ($model->surname !== '')) {
                $userFIO = $userFIO." ".$model->surname;
            }
            $nameIsFull = " ";
            if ((isset($model->name)) && ($model->name !== '')) {
                if ($abbreviated == true)
                {
                    $nameIsFull = "";
                    $userFIO = $userFIO." ".mb_substr($model->name, 0, 1).".";
                }
                else
                    $userFIO = $userFIO." ".$model->name;
            }
            if ((isset($model->middlename)) && ($model->middlename !== '')) {
                if ($abbreviated == true)
                    $userFIO = $userFIO.$nameIsFull.mb_substr($model->middlename, 0, 1).".";
                else
                    $userFIO = $userFIO." ".$model->middlename;
            }

            if ($userFIO == "") {
                $userFIO = $model->email;
            }

            return trim($userFIO);

        }
        else
        {
            return '';
        }
    }


}