<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 19.07.2020
 * Time: 14:42
 */

namespace common\models;

use yii\base\BootstrapInterface;
use Yii;


class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        date_default_timezone_set( 'Europe/Moscow' );

        $cur_user_id = Yii::$app->user->id;

        $cur_user = User::findIdentity($cur_user_id);

        if(isset($cur_user) == true){
            $cur_user->register_activity();
        }

    }
}