<?php
/**
 * page-sippers.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>

        <script src="lib/jq/jquery-1.8.2.js"></script>
        <script src="lib/jq/jquery-ui-1.9.2.js"></script>
        <script src="lib/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="lib/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="lib/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="lib/jq/jquery.cookie.js"></script>

        <link href="lib/smoothness/jquery-ui.css?8" rel="stylesheet" >
        <link href="lib/smoothness/jquery-ui.dropdownchecklist.css" rel="stylesheet" >

        <link href="/cdr/css/common.css?8" rel="stylesheet" >

        <script src="/cdr/js/base.js?8"></script>
        <script src="/cdr/js/filters-form.js?8"></script>
        <script src="/cdr/js/multiselect.js?8"></script>
        <script src="/cdr/js/fixed-header.js?8"></script>
        <script src="/cdr/js/grit.js?8"></script>
        <script src="/cdr/js/jplayer.js?8"></script>


        <script type="text/javascript">
        </script>
    </head>

    <body class="fixed-header">
        <div id="wrapper" >

            <div id="header" class="fixed clear_fix">
                <ul class="menu clear_fix">
                    <li><a href="cdr.php" class="header-icon icon-cdr-big"> Запись разговоров </a></li><li><a href="queue.php" class="header-icon icon-queue-big"> Очереди </a></li><li class=""><a href="timeman.php" class="header-icon icon-timeman-big"> Профиль вызовов </a></li><li><a href="supervisor.php" class="header-icon icon-supervisor-big"> Супервизор </a></li><li class="submenu">
                        <span class="submenu-title"> <a href="settings.php" class="header-icon icon-settings-big"> настройки: </a> </span>
                        <ul>
                            <li class="current"> <a href="settings.php?section=operator"> Операторы  </a> </li>
                            <li class=""> <a href="settings.php?section=queue"> Очереди  </a> </li>
                            <li class=""> <a href="settings.php?section=schedule"> Расписание  </a> </li>
                            <li class=""> <a href="settings.php?section=mode"> Режим работы </a> </li>
                            <li class=""> <a href="settings.php?section=pause"> Паузы </a> </li>
                            <li class=""> <a href="settings.php?section=answering"> Автоинформатор </a> </li>
                        </ul><li class=""><a href="autoinform.php" class="header-icon icon-autoinform-big"> Автоинформатор </a></li>    </ul>
            </div>

            <div id="middle" class="">
                <div class="ui-widget-content edit-content" style="margin-top: 10px; width: 550px;" >
                    <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">Изменить оператора 12331</div>
                    <form method="post">
                        <input type="hidden" name="action" value="edit" />
                        <input type="hidden" name="agentid" value="12331" />

                        <div class="clear clear_fix bigblock">
                            <div class="label fl_l ta_r" style="width: 250px;"><span class="field-required">*</span>ФИО:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="name" value="asdasfsdfsda" /></div>
                        </div>
                        <div class="clear clear_fix mediumblock">
                            <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 1:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="queues1" value="" /></div>
                        </div>
                        <div class="clear clear_fix miniblock">
                            <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 1:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="penalty1" value="0" class="field-number" maxlength="2" /></div>
                        </div>
                        <div class="clear clear_fix mediumblock">
                            <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 2:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="queues2" value="" /></div>
                        </div>
                        <div class="clear clear_fix miniblock">
                            <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 2:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="penalty2" value="0" class="field-number" maxlength="2" /></div>
                        </div>
                        <div class="clear clear_fix mediumblock">
                            <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 3:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="queues3" value="" /></div>
                        </div>
                        <div class="clear clear_fix miniblock">
                            <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 3:</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="penalty3" value="0" class="field-number" maxlength="2" /></div>
                        </div>
                        <div class="clear clear_fix bigblock">
                            <div class="label fl_l ta_r" style="width: 250px;">-</div>
                            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;">
                                <button class="button">Сохранить</button>
                            </div>
                        </div>
                        <div class="clear clear_fix bigblock"></div>
                    </form>
                </div>
                <div class="clear" style="padding-top: 50px;"><hr /></div>
            </div>
        </div>
    </body>
</html>