<?php

$this->title = 'Календарь';

?>

<div id="paramDate" hidden="hidden"><?= $date ?></div>
<div id="paramColorUnused" hidden="hidden"><?= $colorUnused ?></div>
<div id="paramColorNone" hidden="hidden"><?= $colorNone ?></div>


<div class="window window-border window-caption window-h-35">
    <div class="caption-begin window-m-t--9">
        <div id="floatingCirclesGMain" hidden>
            <div class="f_circleG" id="frotateG_01"></div>
            <div class="f_circleG" id="frotateG_02"></div>
            <div class="f_circleG" id="frotateG_03"></div>
            <div class="f_circleG" id="frotateG_04"></div>
            <div class="f_circleG" id="frotateG_05"></div>
            <div class="f_circleG" id="frotateG_06"></div>
            <div class="f_circleG" id="frotateG_07"></div>
            <div class="f_circleG" id="frotateG_08"></div>
        </div>
        <div><?='&nbsp;'?></div>
    </div>
    <div class="caption-text" id="form-caption">Календарь</div>

</div>

<div class="content">
    <div class="window window-border main-info-foto">
        <div class="caption-text w-100" id="caption"><?= $curDate ?></div>
        <div class="main clear">
            <div class="column-10" id="arrow-back">
                <div class="like-table h-576px w-100 ia-background" id="arrow-back-high">
                    <div class="like-cell text-center">
                        <i class="arrow arrow-left"></i>
                    </div>
                </div>
            </div>
            <div class="column-80">
                <div class="table">
                    <div class="row" id="week0">
                        <div class="column-9 h-25px border-1px content-hide col-back-unused-light text-center like-table" id="nweek0">
                            Неделя
                        </div>
                        <div class="column-13 h-25px border-1px content-hide col-back-unused-light text-center" id="Mon">
                            Понедельник
                        </div>
                        <div class="column-13 h-25px border-1px content-hide col-back-unused-light text-center" id="Tue">
                            Вторник
                        </div>
                        <div class="column-13 h-25px border-1px content-hide col-back-unused-light text-center" id="Wed">
                            Среда
                        </div>
                        <div class="column-13 h-25px border-1px content-hide col-back-unused-light text-center" id="Thu">
                            Четверг
                        </div>
                        <div class="column-13 h-25px border-1px content-hide col-back-unused-light text-center" id="Fri">
                            Пятница
                        </div>
                        <div class="column-13 h-25px border-1px content-hide col-back-unused-light text-center" id="Sat">
                            Суббота
                        </div>
                        <div class="column-13 h-25px border-1px-right content-hide col-back-unused-light text-center" id="Sun">
                            Воскресенье
                        </div>
                    </div>
                    <div class="row" id="week1">
                        <div class="column-9 h-110px border-1px content-hide col-back-unused-light like-table" id="nweek1">
                            <div class="like-cell text-center text-s-20px" id="num-week1">
                                1
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day1">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday1">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day2">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday2">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day3">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday3">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day4">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday4">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day5">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday5">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day6">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday6">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-right content-hide" id="day7">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday7">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="week2">
                        <div class="column-9 h-110px border-1px content-hide col-back-unused-light like-table" id="nweek2">
                            <div class="like-cell text-center text-s-20px" id="num-week2">
                                2
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day8">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday8">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day9">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday9">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day10">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday10">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day11">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday11">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day12">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday12">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day13">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday13">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-right content-hide" id="day14">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday14">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="week3">
                        <div class="column-9 h-110px border-1px content-hide col-back-unused-light like-table" id="nweek3">
                            <div class="like-cell text-center text-s-20px" id="num-week3">
                                3
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day15">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday15">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day16">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday16">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day17">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday17">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day18">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday18">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day19">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday19">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day20">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday20">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-right content-hide" id="day21">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday21">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="week4">
                        <div class="column-9 h-110px border-1px content-hide col-back-unused-light like-table" id="nweek4">
                            <div class="like-cell text-center text-s-20px" id="num-week4">
                                4
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day22">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday22">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day23">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday23">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day24">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday24">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day25">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday25">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day26">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday26">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day27">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday27">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-right content-hide" id="day28">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday28">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="week5">
                        <div class="column-9 h-110px border-1px content-hide col-back-unused-light like-table" id="nweek5">
                            <div class="like-cell text-center text-s-20px" id="num-week5">
                                5
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day29">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday29">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day30">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday30">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day31">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday31">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day32">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday32">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day33">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday33">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px content-hide" id="day34">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday34">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-right content-hide" id="day35">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday35">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="week6">
                        <div class="column-9 h-110px border-1px-bottom content-hide col-back-unused-light like-table" id="nweek6">
                            <div class="like-cell text-center text-s-20px" id="num-week6">
                                6
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-bottom content-hide" id="day36">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday36">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-bottom content-hide" id="day37">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday37">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-bottom content-hide" id="day38">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday38">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-bottom content-hide" id="day39">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday39">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-bottom content-hide" id="day40">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday40">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-bottom content-hide" id="day41">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday41">

                                </div>
                            </div>
                        </div>
                        <div class="column-13 h-110px border-1px-all content-hide" id="day42">
                            <div class="column-33 h-40px content-hide like-table">
                                <div class="like-cell text-center text-s-20px" id="nday42">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column-10" id="arrow-forward">
                <div class="like-table h-576px w-100 ia-background" id="arrow-forward-high">
                    <div class="like-cell text-center">
                        <i class="arrow arrow-right"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>