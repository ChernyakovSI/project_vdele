<?php
/**
 * Created by PhpStorm.
 * User: palad
 * Date: 08.04.2020
 * Time: 19:25
 */

namespace common\models;


class GeneralView
{
    public static function getHTML($text)
    {
        return str_replace(array("\r\n", "\r", "\n"), '<br>', $text);
    }
}