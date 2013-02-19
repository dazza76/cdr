<?php
/* AC: v: */

/**
 * operator-test.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>TEST</title>

        <link href="css/jquery-ui.css?1" rel="stylesheet" >
        <link href="css/jquery-ui.dropdownchecklist.css?1" rel="stylesheet" >
        <link href="css/common.css?1" rel="stylesheet" >

        <script src="js/jq/jquery-1.8.2.js"></script>
        <script src="js/jq/jquery-ui-1.9.2.js"></script>
        <script src="js/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="js/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="js/jq/jquery.ui.dropdownchecklist.js"></script>

        <script src="js/common.js?1"></script>
    </head>

    <body class="fixed-header">
        <div id="wrapper">

            <div id="header" class="fixed clear_fix">
                <ul class="menu clear_fix">
                    <li class="current">
                        <a href="#" class="header_icon icon_calls_big"> Запись разговоров </a>
                    </li>
                </ul>
            </div>

            <div id="middle">
                <div class="filters clear_fix">
                    <div class="filter fl_l sep">
                        <div class="label">Оператор (100 штук)</div>
                        <div class="labeled">
                            <select style="width: 200px;">
                                <?php for ($i = 1; $i < 6; $i ++ ) { ?>
                                    <option value="1001">Торосян А.С.</option>
                                    <option value="1002">Данилина В.Ю.</option>
                                    <option value="1003">Борзунова А.К.</option>
                                    <option value="1004">Воскобойникова Е.А.</option>
                                    <option value="1005">Пахомова Л.Г.</option>
                                    <option value="1006">Шкадина О.</option>
                                    <option value="4001">Белкина</option>
                                    <option value="4002">Арутюнян</option>
                                    <option value="2001">Томшина Ю.В.</option>
                                    <option value="2002">Дальнова Т.В.</option>
                                    <option value="3001">Шилко О.Г.</option>
                                    <option value="3002">Тихомирова С.</option>
                                    <option value="5002">Нескромная Н</option>
                                    <option value="3003">Иванова Е.</option>
                                    <option value="3004">Лебеднева М.</option>
                                    <option value="3005">Маслюковская Е.</option>
                                    <option value="5002">Нескромная Н</option>
                                    <option value="5001">Мазунина</option>
                                    <option value="3006">Соколова В.</option>
                                    <option value="5002">Нескромная Н</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                    <div class="filter fl_l sep">
                        <div class="label">Оператор (200 штук)</div>
                        <div class="labeled">
                            <select style="width: 200px;">
                                <?php for ($i = 1; $i < 11; $i ++ ) { ?>
                                    <option value="1001">Торосян А.С.</option>
                                    <option value="1002">Данилина В.Ю.</option>
                                    <option value="1003">Борзунова А.К.</option>
                                    <option value="1004">Воскобойникова Е.А.</option>
                                    <option value="1005">Пахомова Л.Г.</option>
                                    <option value="1006">Шкадина О.</option>
                                    <option value="4001">Белкина</option>
                                    <option value="4002">Арутюнян</option>
                                    <option value="2001">Томшина Ю.В.</option>
                                    <option value="2002">Дальнова Т.В.</option>
                                    <option value="3001">Шилко О.Г.</option>
                                    <option value="3002">Тихомирова С.</option>
                                    <option value="5002">Нескромная Н</option>
                                    <option value="3003">Иванова Е.</option>
                                    <option value="3004">Лебеднева М.</option>
                                    <option value="3005">Маслюковская Е.</option>
                                    <option value="5002">Нескромная Н</option>
                                    <option value="5001">Мазунина</option>
                                    <option value="3006">Соколова В.</option>
                                    <option value="5002">Нескромная Н</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                </div>

                <div class="filters clear_fix bigblock of_h">
                    <div class="fl_l" style="padding-right: 15px;">
                        Найдено: 0    </div>
                    <div class="pg-pages fl_r">
                        <div class="pg-pages fl_r"></div>    </div>
                </div>


                <div class="clear clear_fix bigblock">
                    <table class="grid">
                        <thead>
                            <tr>
                                <!--  data-sort="" data-desc=""  -->
                                <th style="width: 60px;">Напр.</th>
                                <th style="width: 150px;">Дата</th>
                                <th style="width: 150px;">Источник</th>
                                <th style="width: 150px;">Назначение</th>
                                <th style="width: 150px;">Оператор</th>
                                <th style="width: 135px;">Запись</th>
                                <th style="width: 70px;">Время</th>
                                <th style="">Комментарий</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>


                <div style="padding-top: 50px;"><hr /></div>
            </div>
        </div>
    </body>
</html>