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

class SendController extends Controller
{

    public function actionIndex()
    {
        echo '777';
    }

    public function actionMail(){
        echo '111';

        DialogUsers::renewSendedLettersAboutUnreadMessages();
        $unreadDialogs = DialogUsers::getArrayOfUsersWithUnreadDialogs();

        echo '222';

        /*foreach ($unreadDialogs as $DialogUser){
            $anotherUser = DialogUsers::getAnotherUserInDialog($DialogUser['id_dialog'], $DialogUser['id_user']);

            $userSender = User::findOne($anotherUser['id_user']);

            $home_url = Yii::$app->params['doman'];
            //Формирование ссылки на страницу поста
            $link = '/dialog?id=';
            $full_link = $home_url.$link.$anotherUser['id_user'];

            echo $full_link;

            $msg = "Здравствуйте! У вас есть непрочитанные сообщения от пользователя ".$userSender->getFIO($anotherUser['id_user']).". Перейдите по ссылке, чтобы прочитать сообщение: ".$full_link;
            $msg_html  = "<html><body style='font-family:Arial,sans-serif;'>";
            $msg_html .= "<h3 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Здравствуйте! У вас есть непрочитанные сообщения от пользователя  " . $userSender->getFIO($anotherUser['id_user']) . ".</h3>\r\n";
            $msg_html .= "<p><strong>Перейдите по ссылке, чтобы прочитать сообщение: </strong><a href='". $full_link ."'>$full_link</a></p>\r\n";
            $msg_html .= "</body></html>";

            $subject = "Новое сообщение от ".$userSender->getFIO($anotherUser['id_user']);


            Mailer::sendLetter($subject, $msg_html, 'paladin_cool@inbox.ru');
            echo $full_link;

            $dialog = DialogUsers::findOne($DialogUser['id']);
            $dialog->setSended(2);
        }*/

        return 0;
    }
}