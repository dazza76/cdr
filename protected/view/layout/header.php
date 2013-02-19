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


    <ul class="menu clear_fix">
        <li class="<?php echo $c['cdr']; ?>">
            <a href="cdr.php" class="header-icon icon-calls-big"> Запись разговоров </a>
        </li>
<?php  // Очереди
if ($c['queue']) {
    $sm[$this->chart]='current';
?>
        <li class="submenu">
            <span class="submenu_title"> <a href="queue.php" class="header-icon icon-charts-big"> Очереди: </a> </span>
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
            <a href="queue.php" class="header-icon icon-charts-big"> Очереди </a>
        </li>
<?php }
// Очереди --END
?>
        <li class="<?php echo $c['timeman']; ?>">
            <a href="timeman.php" class="header-icon icon-reports-big"> Профиль вызовов </a>
        </li>
       <li class="<?php echo $c['settings']; ?>">
            <a href="settings.php" class="header-icon icon-settings-big"> Настройки </a>
        </li>

    </ul>
