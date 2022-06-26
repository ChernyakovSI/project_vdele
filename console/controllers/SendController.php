<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 18.10.2020
 * Time: 15:52
 */

namespace console\controllers;

use common\models\DialogUsers;
use common\models\User;
use yii\console\Controller;
use Yii;
use common\models\Mailer;
//++ 1-2-3-002 11/05/2022
use common\models\MailUser;
//-- 1-2-3-002 11/05/2022

class SendController extends Controller
{

    public function actionIndex()
    {
        echo '777';
    }

    public function actionMail(){
        DialogUsers::renewSendedLettersAboutUnreadMessages();
        $unreadDialogs = DialogUsers::getArrayOfUsersWithUnreadDialogs();

        foreach ($unreadDialogs as $DialogUser){
            $anotherUser = DialogUsers::getAnotherUserInDialog($DialogUser['id_dialog'], $DialogUser['id_user']);

            $userSender = User::findOne($anotherUser['id_user']);
            $userReceiver = User::findOne($DialogUser['id_user']);

            $home_url = Yii::$app->params['doman'];
            //Формирование ссылки на страницу поста
            $link = 'dialog?id=';
            $full_link = $home_url.$link.$anotherUser['id_user'];

            //$msg = "Здравствуйте! У вас есть непрочитанные сообщения от пользователя ".$userSender->getFIO($anotherUser['id_user']).". Перейдите по ссылке, чтобы прочитать сообщение: ".$full_link;
            $msg_html  = "<html><body style='font-family:Arial,sans-serif;'>";
            $msg_html .= "<h3 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Здравствуйте! У вас есть непрочитанные сообщения от пользователя  " . $userSender->getFI($anotherUser['id_user']) . ".</h3>\r\n";
            $msg_html .= "<p><strong><a href='". $full_link ."'>Перейдите по ссылке</a>, чтобы прочитать сообщение.</strong></p>\r\n";
            $msg_html .= "</body></html>";

            $subject = "Новое сообщение от ".$userSender->getFI($anotherUser['id_user']);

            Mailer::sendLetter($subject, $msg_html, $userReceiver->getEmail());

            $dialog = DialogUsers::findOne($DialogUser['id']);
            $dialog->setSended(2);
        }

        //++ 1-2-3-002 11/05/2022
        $mailUsers = MailUser::getUsersForMail();
        $qua = 0;
        foreach ($mailUsers as $mailUser){
            if ($qua >= 3) {
                break;
            }

            $userReceiver = User::findOne($mailUser['id_user']);

            $msg_html  = "<html><body style='font-family:Arial,sans-serif;'>";
            $msg_html .= $mailUser['text'];
            $msg_html .= "</body></html>";

            $subject = $mailUser['title'];

            Mailer::sendLetter($subject, $msg_html, $userReceiver->getEmail());

            $dialog = MailUser::findOne($mailUser['id']);
            $dialog->setSended(1);

            $qua = $qua + 1;
        }
        //-- 1-2-3-002 11/05/2022

        return 0;
    }
}