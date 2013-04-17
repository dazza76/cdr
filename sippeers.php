<?php
require_once 'protected/bootstrap.php';

$app = new Application();

//if (!((isset($_POST["submit"])) && ($_POST["submit"] == "OK"))) {
//    $html_out = "<form action=\"add.php\" method=\"POST\"><table border=1><tr><td colspan=2>Основные</td></tr>";
//    foreach ($main as $value)
//        $html_out .= ("<tr><td>$value</td><td><input type=text name=\"$value\"/></td></tr>");
//    $html_out .= "<tr><td colspan=2>Расширенные</td></tr>";
//    foreach ($expert as $value)
//        $html_out .= ("<tr><td>$value</td><td><input type=text name=\"$value\"/></td></tr>");
//    $html_out .= "<tr><td colspan=2><input type=submit name=submit value=\"OK\"/></td></tr></table></form>";
//    echo $html_out;
//} else {
//    foreach ($_POST as $key => $value) {
//        if ((!is_numeric($key)) && ($key != "submit") && ($value)) {
//            $valuenames .= ",`$key`";
//            $values .= ",'$value'";
//        };
//    };
//    $valuenames = "(" . substr($valuenames, 1) . ")";
//    $values = "(" . substr($values, 1) . ")";
//    echo $query = "INSERT INTO sippeers $valuenames VALUES $values;";
//	if(mysql_query($query,$dbconn))
//    echo "Successful";
//	else
//		echo mysql_error();
//}

if ($_POST['action']) {
    header("Content-Type: text/plain; charset=UTF-8");


    $fields = array_merge(Sippeer::$main, Sippeer::$expert);
    $values = array();
    switch ($_POST['action']) {
        case 'add':
            foreach ($fields as $value) {
                $f = '`' . $value . '`';
                if (!empty($_POST[$value])) {
                    $values[$f] = $_POST[$value];
                }
            }
            App::Db()->createCommand()->insert()->into('sippeers')
                    ->values($values)
                    ->query();

            if (!App::Db()->success) {
                exit(App::Db()->error);
                exit();
            }
            break;

        case 'edit':
            $id = $_POST['id'];
            $values_null = array();
            foreach ($fields as $value) {
                $f = '`' . $value . '`';
                if (!empty($_POST[$value])) {
                    $values[$f] = $_POST[$value];
                } else {
                    $values_null[$f] = 'NULL';
                }
            }
            $cmd = App::Db()->createCommand()->update('sippeers')
                    ->set($values)
                    ->set($values_null, false)
                    ->addWhere('id', $id)
                    ->ignore()
                    ->limit(1);
            echo $cmd->toString();
            $cmd->query();
            break;

        default:
            break;
    }

    App::location('sippeers');

    exit();
}

switch ($_GET['page']) {
    case 'add':
        $page = 'add';
        break;
    case 'edit':
        $page = 'edit';
        break;
    default:
        $page = 'list';
        break;
}

$count = App::Db()->createCommand()->select('COUNT(id) AS `count`')
        ->from('sippeers')
        ->query()
        ->fetch();
$count = $count['count'];
$limit = 30;
$offset = (int) $_GET['offset'];


$sippeers = App::Db()->createCommand()->select()
        ->from('sippeers')
        ->limit($limit)
        ->offset($offset)
        ->query();

$id = (int) $_GET['id'];
$sip = App::Db()->createCommand()->select()
        ->from('sippeers')
        ->addWhere('id', $id)
        ->query()
        ->fetchAssoc();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Статистика операторов</title>

<!--        <script src="lib/jq/jquery-1.8.2.js"></script>
        <script src="lib/jq/jquery-ui-1.9.2.js"></script>
        <script src="lib/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="lib/jq/jquery.ui.timepicker.addon-0.7.3.js"></script>
        <script src="lib/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="lib/jq/jquery.cookie.js"></script>

        <link href="lib/smoothness/jquery-ui.css?12" rel="stylesheet" >
        <link href="lib/smoothness/jquery-ui.dropdownchecklist.css" rel="stylesheet" >

        <link href="/cdr/css/common.css?12" rel="stylesheet" >-->

        <script src="/cdr/js/base.js?12"></script>
        <script src="/cdr/js/datetimepicker.js?12"></script>
        <script src="/cdr/js/filters-form.js?12"></script>
        <script src="/cdr/js/multiselect.js?12"></script>
        <script src="/cdr/js/fixed-header.js?12"></script>
        <script src="/cdr/js/grit.js?12"></script>
        <script src="/cdr/js/jplayer.js?12"></script>


        <script type="text/javascript">
        </script>
    </head>

    <body class="fixed-header">
        <div id="wrapper" >

            <div id="header" class="fixed clear_fix">
                <ul class="menu clear_fix">
                    <li>
                        <a href="?page=list" class="header-icon icon-cdr-big"> Sippeers </a>
                    </li>
                </ul>
            </div>

            <div id="middle" class="">
                <?php if ($page == "list") { ?>
                    <div class="filters clear_fix">
                        <div class="clear_fix bigblock of_h">
                            <div class="fl_l" style="padding-right: 15px;">
                                Всего: <?php echo $count;  ?>
                            </div>
                            <?php echo Utils::pagenator($count, $offset, $limit); ?>
                            <div class="fl_r" style="margin-right:15px;"><a href="?page=add" class="icon icon-add">добавить</a></div>
                            <div class="pg-pages fl_r">  </div>
                        </div>
                    </div>


                    <div class="clear clear_fix">
                        <table class="grid">
                            <thead>
                                <tr>
                                    <th> Удалить</th>
                                    <th> Изменить</th>
                                    <th>id</th>
                                    <?php
                                    foreach (Sippeer::$main as $value) {
                                        echo "<th>$value</th>";
                                    }
                                    foreach (Sippeer::$expert as $value) {
                                        echo "<th>$value</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($sip = $sippeers->fetchAssoc()) {
                                    ?>
                                    <tr>
                                        <td class="image-link"><a  onclick="return false;" class="icon icon-delete"></a></td>
                                        <td class="image-link"><a href="?page=edit&id=<?php echo $sip['id']; ?>" class="icon icon-edit"></a></td>
                                        <td><?php echo $sip['id']; ?></td>
                                        <?php
                                        foreach (Sippeer::$main as $value) {
                                            echo "<td>{$sip[$value]}</td>";
                                        }
                                        foreach (Sippeer::$expert as $value) {
                                            echo "<td>{$sip[$value]}</td>";
                                        }
                                        ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>

                <?php if ($page == "add") { ?>
                    <div class="ui-widget-content edit-content" style="margin-top: 10px;" >
                        <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">SIP</div>
                        <form method="post">
                            <input type="hidden" name="action" value="add" />

                            <div class="fl_l">
                                <div class="clear clear_fix bigblock" style="padding-left: 155px;">Основные</div>
                                <?php foreach (Sippeer::$main as $value) { ?>
                                    <div class="clear clear_fix mediumblock">
                                        <div class="label fl_l ta_r" style="width: 150px;"><?php echo $value; ?>:</div>
                                        <div class="labeled fl_l" style="width: 200px; margin-left: 5px;"><input type="text" name="<?php echo $value; ?>" value="" /></div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="fl_l">
                                <div class="clear clear_fix bigblock" style="padding-left: 155px;">Расширенные</div>
                                <?php foreach (Sippeer::$expert as $value) { ?>
                                    <div class="clear clear_fix mediumblock">
                                        <div class="label fl_l ta_r" style="width: 150px;"><?php echo $value; ?>:</div>
                                        <div class="labeled fl_l" style="width: 200px; margin-left: 5px;"><input type="text" name="<?php echo $value; ?>" value="" /></div>
                                    </div>
                                <?php } ?>
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
                <?php } ?>

                <?php if ($page == "edit") { ?>
                    <div class="ui-widget-content edit-content" style="margin-top: 10px;" >
                        <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">SIP</div>
                        <form method="post">
                            <input type="hidden" name="action" value="edit" />
                            <input type="hidden" name="id" value="<?php echo $sip['id']; ?>" />

                            <div class="fl_l">
                                <div class="clear clear_fix bigblock" style="padding-left: 155px;">Основные</div>
                                <?php foreach (Sippeer::$main as $value) { ?>
                                    <div class="clear clear_fix mediumblock">
                                        <div class="label fl_l ta_r" style="width: 150px;"><?php echo $value; ?>:</div>
                                        <div class="labeled fl_l" style="width: 200px; margin-left: 5px;"><input type="text" name="<?php echo $value; ?>" value="<?php echo $sip[$value]; ?>" /></div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="fl_l">
                                <div class="clear clear_fix bigblock" style="padding-left: 155px;">Расширенные</div>
                                <?php foreach (Sippeer::$expert as $value) { ?>
                                    <div class="clear clear_fix mediumblock">
                                        <div class="label fl_l ta_r" style="width: 150px;"><?php echo $value; ?>:</div>
                                        <div class="labeled fl_l" style="width: 200px; margin-left: 5px;"><input type="text" name="<?php echo $value; ?>" value="<?php echo $sip[$value]; ?>" /></div>
                                    </div>
                                <?php } ?>
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
                <?php } ?>




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