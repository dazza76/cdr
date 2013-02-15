<?php
/* AC: v: */

/**
 * header.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
$c[$this->page] = 'current';
?>

<div id="header" class="fixed clear_fix">
    <ul class="menu clear_fix">
        <li class="<?php echo $c['cdr']; ?>">
            <a href="cdr.php" class="header_icon icon_calls_big"> Запись разговоров </a>
        </li>
<?php
if ($c['queue']) {
    $sm[$this->chart]='current';
?>
        <li class="submenu">
            <span class="submenu_title"> <a href="queue.php" class="header_icon icon_charts_big"> Очереди: </a> </span>
            <ul>
                <li class="<?php echo $sm['arbit']; ?>"> <a href="queue.php"> Произвольно </a> </li>
                <li class="<?php echo $sm['day']; ?>"> <a href="queue.php?chart=day"> Суточный </a> </li>
                <li class="<?php echo $sm['week']; ?>"> <a href="queue.php?chart=week"> Недельный </a> </li>
                <li class="<?php echo $sm['month']; ?>"> <a href="queue.php?chart=month"> Месячный </a> </li>
                <li class="<?php echo $sm['compare']; ?>"> <a href="queue.php?chart=compare"> Сравнение </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="queue.php" class="header_icon icon_charts_big"> Очереди </a>
        </li>
<?php } ?>
        <li class="<?php echo $c['timeman']; ?>">
            <a href="timeman.php" class="header_icon icon_reports_big"> Профиль вызовов </a>
        </li>
    </ul>
</div>
