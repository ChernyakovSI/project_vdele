<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 18.10.2020
 * Time: 16:15
 */

namespace common\models;

use Yii;


class Mailer
{
    public static function sendLetter($subject, $message, $emailTo)
    {
        //Устанавливаем кодировку заголовка письма и кодируем его
        $subject = "=?utf-8?B?".base64_encode($subject)."?=";

        $emailFrom = Yii::$app->params['robotEmail'];//"robot@yavdele.net";

        echo $emailFrom;

        Yii::$app->mailer->compose()
            ->setTo($emailTo)
            ->setFrom([$emailFrom => "Я в деле"])
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->send();

    }
}