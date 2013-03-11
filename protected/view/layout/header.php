<?php
/* AC: v: */

/**
 * header file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
$c[$this->getPage()] = 'current';
?>

<div id="header" class="fixed clear_fix">
    <ul class="menu clear_fix">

<?php  // Записи
if ($c['cdr']) {
    $sm[$this->getSection()]='current';
?>
        <li class="submenu">
            <span class="submenu-title"> <a href="cdr" class="header-icon icon-calls-big"> Запись разговоров: </a> </span>
            <ul>
                <li class="<?php echo $sm['calls']; ?>"> <a href="cdr"> Звонки </a> </li>
                <li class="<?php echo $sm['answering']; ?>"> <a href="cdr?section=answering"> Автоинформатор </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="cdr" class="header-icon icon-calls-big"> Запись разговоров </a>
        </li>
<?php }
// Записи --END
?>


<?php  // Очереди
if ($c['queue']) {
    $sm[$this->getSection()]='current';
?>
        <li class="submenu">
            <span class="submenu-title"> <a href="queue" class="header-icon icon-charts-big"> Очереди: </a> </span>
            <ul>
                <li class="<?php echo $sm['arbit']; ?>"> <a href="queue"> Произвольно </a> </li>
                <li class="<?php echo $sm['day']; ?>"> <a href="queue?section=day"> Суточный </a> </li>
                <li class="<?php echo $sm['week']; ?>"> <a href="queue?section=week"> Недельный </a> </li>
                <li class="<?php echo $sm['month']; ?>"> <a href="queue?section=month"> Месячный </a> </li>
                <li class="<?php echo $sm['compare']; ?>"> <a href="queue?section=compare"> Сравнение </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="queue" class="header-icon icon-charts-big"> Очереди </a>
        </li>
<?php }
// Очереди --END
?>
        <li class="<?php echo $c['timeman']; ?>">
            <a href="timeman" class="header-icon icon-reports-big"> Профиль вызовов </a>
        </li>



<?php  // настройки
if ($c['settings']) {
    $sm[$this->getSection()]='current';
?>
        <li class="submenu">
            <span class="submenu-title"> <a href="settings" class="header-icon icon-settings-big"> Настройки: </a> </span>
            <ul>
                <li class="<?php echo $sm['operator']; ?>"> <a href="settings?section=operator"> Операторы </a> </li>
                <li class="<?php echo $sm['queue']; ?>"> <a href="settings?section=queue"> Очереди </a> </li>
                <li class="<?php echo $sm['schedule']; ?>"> <a href="settings?section=schedule"> Расписание </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="settings" class="header-icon icon-settings-big"> Настройки </a>
        </li>
<?php }
// настройки --END
?>


<?php  // Супервизоры
if ($c['supervisor']) {
    $sm[$this->getSection()]='current';
?>
        <li class="submenu">
            <span class="submenu-title"> <a href="supervisor" class="header-icon icon-monitor-big"> Супервизоры: </a> </span>
            <ul>
                <li class="<?php echo $sm['queue']; ?>"> <a href="supervisor?section=queue"> Очереди </a> </li>
                <li class="<?php echo $sm['operator']; ?>"> <a href="supervisor?section=operator"> Операторы </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="supervisor" class="header-icon icon-monitor-big"> Супервизоры </a>
        </li>
<?php }
// Супервизоры --END
?>




    </ul>
</div>
