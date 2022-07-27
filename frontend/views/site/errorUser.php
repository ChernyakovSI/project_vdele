<?php

//$this->title = $name;
?>
<div class="site-error">

    <h1>Упс... Кажется, что-то пошло не так</h1>

    <div class="alert alert-danger">
        В результате ваших последних действий произошла непредвиденая ошибка! Информация об ошибке уже передана в технический центр
        и в ближайшее время будет рассмотрена.
        <br>
        <br>
        Для получения персональной помощи или для передачи более детальной информации по проблеме
        просьба обращаться в <b><a href="<?= Yii::$app->params['doman'] ?>dialog?id=1">поддержку</a></b>. Это поможет быстрее разобраться с ситуацией.
    </div>

    <div class="item-foto">
        <div class="div-center w-183px">
            <img src="/data/img/tech/error2.jpg">
        </div>
    </div>

</div>