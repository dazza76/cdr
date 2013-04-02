<?php
require_once 'protected/bootstrap.php';

$app = new Application();

$fromdate = FiltersValue::parseDatetime($_GET['fromdate']);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Статистика операторов</title>

        <script src="lib/jq/jquery-1.8.2.js"></script>
        <script src="lib/jq/jquery-ui-1.9.2.js"></script>
        <script src="lib/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="lib/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="lib/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="lib/jq/jquery.cookie.js"></script>

        <link href="lib/smoothness/jquery-ui.css?8" rel="stylesheet" >
        <link href="lib/smoothness/jquery-ui.dropdownchecklist.css" rel="stylesheet" >

        <link href="/cdr/css/common.css?<?php echo App::Config()->v; ?>" rel="stylesheet" >

        <script src="/cdr/js/base.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/filters-form.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/multiselect.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/fixed-header.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/grit.js?<?php echo App::Config()->v; ?>"></script>
        <script src="/cdr/js/jplayer.js?<?php echo App::Config()->v; ?>"></script>


        <script type="text/javascript">
        </script>
    </head>

    <body class="fixed-header">
        <div id="wrapper" >

            <div id="header" class="fixed clear_fix">
                <ul class="menu clear_fix">
                    <li>
                        <a href="#" class="header-icon icon-cdr-big"> Месячный отчет </a>
                    </li>
                </ul>
            </div>

            <div id="middle" class="">

                <div class="filters clear_fix">
                    <form method="get" action="" class="of_h">
                        <input type="hidden" name="section" value="answering" />
                        <div class="filter fl_l sep">
                            <div class="label">Дата</div>
                            <div class="labeled">
                                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                            </div>
                        </div>

                        <div class="filter fl_l sep">
                            <div class="label">Оператор</div>
                            <div class="labeled">
                                <select name="oper" size="1"  default="<?php echo $oper; ?>">
                                    <?php echo QueueAgent::showOperslist(); ?>
                                </select>
                            </div>
                        </div>

                        <div class="filter fl_l">
                            <div class="labeled">
                                <input type="submit" name="search" id="button-search" class="button button-search" class="button" value="Показать" />
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="" />
                        <input type="hidden" name="desc" value="" />
                        <input type="hidden" name="offset" value="" />
                    </form>
                </div>

                <div class="filters clear_fix bigblock of_h">
                    <table class="grid" htable="1">
                        <thead>
<tr>
    <th>Оператор</hd>
    <th>Входящие, шт.</hd>
    <th>Исходящие, шт.</hd>
    <th>Всего вызовов, шт.</hd>
    <th>Простой, ЧЧ:ММ:СС</hd>
    <th>Обработка, ЧЧ:ММ:СС</hd>
    <th>Перерыв, ЧЧ:ММ:СС</hd>
    <th>Долгое поднятие трубки, шт.</hd>
    <th>Ср. вр. разговора, сек.</hd>
    <th>Ср. вр. подн. трубки, сек.</hd>
</tr>
                        </thead>
                    </table>
                </div>

                <div class="clear clear_fix">
                    <table class="grid" htable="1">
                        <thead>
                            <tr class="b-head">
                                <th style=""             > </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                                <th style="width: 150px;"> </th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>




                <div class="clear" style="padding-top: 50px;"><hr /></div>
            </div>
        </div>
    </body>
</html>
<?php
Log::trace('Объем занимаемой памяти: ' . ACUtils::getMemoryString(ACUtils::getMemoryUsage()));
Log::trace('Запросов MySQL: ' . App::Db()->getNumQuery());
Log::trace('Время обработки MySQL: ' . sprintf(" %01.6f", App::Db()->getTimeQuery()));
Log::trace('Время работы скрипта : ' . sprintf(" %01.6f", ACUtils::getExecutionTime()));

Log::render();
