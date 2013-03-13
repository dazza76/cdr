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
            <span class="submenu-title"> <a href="<?php echo Utils::pageUrl('cdr'); ?>" class="header-icon icon-calls-big"> Запись разговоров: </a> </span>
            <ul>
                <li class="<?php echo $sm['calls']; ?>"> <a href="<?php echo Utils::pageUrl('cdr'); ?>"> Звонки </a> </li>
                <li class="<?php echo $sm['answering']; ?>"> <a href="<?php echo Utils::pageUrl('cdr'); ?>?section=answering"> Автоинформатор </a> </li>
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
            <span class="submenu-title"> <a href="<?php echo Utils::pageUrl('queue'); ?>" class="header-icon icon-charts-big"> Очереди: </a> </span>
            <ul>
                <li class="<?php echo $sm['arbit']; ?>"> <a href="<?php echo Utils::pageUrl('queue'); ?>"> Произвольно </a> </li>
                <li class="<?php echo $sm['day']; ?>"> <a href="<?php echo Utils::pageUrl('queue'); ?>?section=day"> Суточный </a> </li>
                <li class="<?php echo $sm['week']; ?>"> <a href="<?php echo Utils::pageUrl('queue'); ?>?section=week"> Недельный </a> </li>
                <li class="<?php echo $sm['month']; ?>"> <a href="<?php echo Utils::pageUrl('queue'); ?>?section=month"> Месячный </a> </li>
                <li class="<?php echo $sm['compare']; ?>"> <a href="<?php echo Utils::pageUrl('queue'); ?>?section=compare"> Сравнение </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="<?php echo Utils::pageUrl('queue'); ?>" class="header-icon icon-charts-big"> Очереди </a>
        </li>
<?php }
// Очереди --END
?>
        <li class="<?php echo $c['timeman']; ?>">
            <a href="<?php echo Utils::pageUrl('timeman'); ?>" class="header-icon icon-reports-big"> Профиль вызовов </a>
        </li>



<?php  // настройки
if ($c['settings']) {
    $sm[$this->getSection()]='current';
?>
        <li class="submenu">
            <span class="submenu-title"> <a href="<?php echo Utils::pageUrl('settings'); ?>" class="header-icon icon-settings-big"> Настройки: </a> </span>
            <ul>
                <li class="<?php echo $sm['operator']; ?>"> <a href="<?php echo Utils::pageUrl('settings'); ?>?section=operator"> Операторы </a> </li>
                <li class="<?php echo $sm['queue']; ?>"> <a href="<?php echo Utils::pageUrl('settings'); ?>?section=queue"> Очереди </a> </li>
                <li class="<?php echo $sm['schedule']; ?>"> <a href="<?php echo Utils::pageUrl('settings'); ?>?section=schedule"> Расписание </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="<?php echo Utils::pageUrl('settings'); ?>" class="header-icon icon-settings-big"> Настройки </a>
        </li>
<?php }
// настройки --END
?>


<?php  // Супервизоры
if ($c['supervisor']) {
    $sm[$this->getSection()]='current';
?>
        <li class="submenu">
            <span class="submenu-title"> <a href="<?php echo Utils::pageUrl('supervisor'); ?>" class="header-icon icon-monitor-big"> Супервизор: </a> </span>
            <ul>
                <li class="<?php echo $sm['queue']; ?>"> <a href="<?php echo Utils::pageUrl('supervisor'); ?>?section=queue"> Очереди </a> </li>
                <li class="<?php echo $sm['operator']; ?>"> <a href="<?php echo Utils::pageUrl('supervisor'); ?>?section=operator"> Операторы </a> </li>
            </ul>
        </li>
<?php } else { ?>
        <li class="">
            <a href="<?php echo Utils::pageUrl('supervisor'); ?>" class="header-icon icon-monitor-big"> Супервизор </a>
        </li>
<?php }
// Супервизоры --END
?>




    </ul>
</div>
