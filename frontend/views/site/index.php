<?php

/* @var $this yii\web\View class="site-index"*/

$this->title = 'Я в деле';

?>
<div class="content">
    <!--<div class="background-start">
        <img src="img/start.png" class="opacity-img">
    </div>-->
    <div class="window window-border">
        <div class="text-font text-center text-bold">
            Платформа "Я в деле"
        </div>
        <div class="text-font text-justify">
            &nbsp;&nbsp;&nbsp;&nbsp;"Я в деле" - это цифровое пространство для комфортного хранения информации, учета и
            анализа данных в 8 основных сферах жизни. Сайт создан для людей, которые хотят привнести структуру и
            порядок в свою жизнь, держать важные направления деятельности под контролем и сохранять баланс во всем.
            <br>
            <br>
            &nbsp;(Платформа не адаптирована под мобильные устройства)
            <br>
            <br>
            &nbsp;Основные сферы жизни, на котором реализуется философия сайта:
            <div class="slider-wrapper">
                <input type="radio" name="point" id="slide1" checked>
                <input type="radio" name="point" id="slide2">
                <input type="radio" name="point" id="slide3">
                <input type="radio" name="point" id="slide4">
                <input type="radio" name="point" id="slide5">
                <input type="radio" name="point" id="slide6">
                <input type="radio" name="point" id="slide7">
                <input type="radio" name="point" id="slide8">
                <div class="slider">
                    <div class="slides slide1 window-border col-back-inn">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/inn-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>1. Личностная сфера.</div>
                                <div>- формирование характера и полезных привычек. Личностный рост
                                    <br>
                                   - порядок и структура в себе, в окружающем пространстве, в делах
                                    <br>
                                    - ведение ежедневника, контроль и планирование
                                    <br>
                                    - личный кодекс жизни (конституция)
                                    <br>
                                    - поддержание дисциплины
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide2 window-border col-back-fin">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/fin-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>2. Финансовая сфера.</div>
                                <div>- финансовая грамотность
                                    <br>
                                    - увеличение количества и качества источников дохода
                                    <br>
                                    - учет и анализ доходов и расходов
                                    <br>
                                    - здоровая экономия
                                    <br>
                                    - создание и реализация накоплений
                                    <br>
                                    - инвестирование и преумножение капитала
                                    <br>
                                    - стратегия пути к финансовой свободе</div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide3 window-border col-back-hea">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/hea-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>3. Сфера здоровья и тела.</div>
                                <div>- профилактика заболеваний и регулярные проверки здоровья
                                    <br>
                                    - своевременное лечение
                                    <br>
                                    - здоровый образ жизни (отдых, перерывы, гигиена, правильное питание, здоровый сон, спорт)
                                    <br>
                                    - развитие выносливости, гибкости и/или силы
                                    <br>
                                    - красота и эстетика тела</div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide4 window-border col-back-rel">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/rel-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>4. Социально-эмоциональная сфера.</div>
                                <div>- получение важных эмоций в общении с окружающими людьми
                                    <br>
                                    - построение близких, доверительных отношений
                                    <br>
                                    - семья, друзья, партнеры, полезные контакты, выбор окружения
                                    <br>
                                    - навыки коммуникации, "Выиграл-Выиграл", лидерские навыки, активное слушание, эмпатия и другие навыки
                                    <br>
                                    - признание, публичность, блоггинг
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide5 window-border col-back-edu">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/edu-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>5. Образовательная сфера</div>
                                <div>- регулярное чтение книг
                                    <br>
                                    - постоянное познание нового и развитие любознательности
                                    <br>
                                    - совершенствование имеющихся навыков
                                    <br>
                                    - регулярный сбор информации и новостей в сфере интересов
                                    <br>
                                    - постановка гипотез, пробы и ошибки, открытие нового</div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide6 window-border col-back-rea">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/rea-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>6. Cфера самореализации.</div>
                                <div>- формирование и реализация своей миссии
                                    <br>
                                    - дать миру что-то уникальное, оставить свой след
                                    <br>
                                    - заниматься тем, что нравится и быть полезным
                                    <br>
                                    - быть лучшим в своем деле, стремиться к этому
                                    <br>
                                    - творчество, смелость, уникальность
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide7 window-border col-back-psy">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/psy-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>7. Духовная сфера.</div>
                                <div>- познание себя (постоянный процесс)
                                    <br>
                                    - осознанная жизнь, воля, совесть
                                    <br>
                                    - вера и источники силы
                                    <br>
                                    - прохождение своего жизненного пути
                                    <br>
                                    - состояние внутреннего счастья и любви
                                    <br>
                                    - непривязанность и благодарность
                                    <br>
                                    - автобиография
                                    <br>
                                    - родовая династия (общие ценности, традиции, безопасность, поддержка)
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slides slide8 window-border col-back-tra">
                        <div class="grid-slider">
                            <div class="item-foto"><img src="/data/img/tech/tra-intro.jpg" style="width: 350px"></div>
                            <div class="text-font item-text">
                                <div>8. Сфера яркости жизни</div>
                                - мечты
                                <br>
                                - любимое занятие
                                <br>
                                - путешествия
                                <br>
                                - погружение в другие культуры
                                <br>
                                - расширение кругозора через новые ощущения и чувственный опыт
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="controls">
                    <label for="slide1"></label>
                    <label for="slide2"></label>
                    <label for="slide3"></label>
                    <label for="slide4"></label>
                    <label for="slide5"></label>
                    <label for="slide6"></label>
                    <label for="slide7"></label>
                    <label for="slide8"></label>
                </div>
            </div>
   &nbsp;
            <br>
            <br>
            &nbsp; Буду рад услышать Вашу обратную связь, все имеющиеся проблемы в работе сайта, а также Ваши пожелания в том,
            что в приоритете стоит реализовывать и какие еще функциональности Вам были бы интересны.
            <!--<br>
            <br>
            - Ознакомиться с презентацией проекта можно <u><a href="https://vk.cc/9tVs05" target="_blank">здесь</a></u>.-->
        </div>
        <div class="text-font text-right">
            <div class="window-button-panel">
               <a href="/login"><div class="window-button-in-panel window-border">Решайся и присоединяйся!</div></a>
            </div>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
    </div>

</div>

