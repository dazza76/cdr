<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this OperatorController */
?>

<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input type="hidden" name="section" value="operlog" />
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y'); ?>" class="datepicker" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y'); ?>" class="datepicker" >
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Оператор</div>
            <div class="labeled">
                <select name="oper" size="1"  default="<?php echo $this->oper; ?>">
                    <option value="" selected="selected">выберите оператора</option>
                    <?php echo QueueAgent::showOperslist(false); ?>
                </select>
            </div>
        </div>

        <!--
        <div class="filter fl_l sep">
            <div class="label">Действие</div>
            <div class="labeled">
                <select name="oaction" size="1"  default="<?php echo $this->oaction; ?>">
                    <option value="0">все</option>
                    <option value="1">вызовы</option>
                    <option value="2">действия</option>
                </select>
            </div>
        </div>
        -->
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

<div class="clear clear_fix">
    <table class="grid tr-hover" style="width: 900px;">
        <thead>
            <tr>
                <td class="head"  style="width: 150px;">Дата</td>
                <td class="head"  style="width: 150px;">Вход</td>
                <td class="head"  style="width: 150px;">Выход</td>
                <td class="head"  style="width: 150px;">В системе</td>
                <td class="head"  style="">?</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $day_names = array('Вск', 'Пнд', 'Втр', 'Срд', 'Чтв', 'Птн', 'Сбт');
            foreach ($this->dataResult as $day => $dayLogArr) {
                $dayLog = $dayLogArr['logs'];
                $c = @count($dayLog);
                $dname = $day. " (".$day_names[date("w", strtotime($day))].")";
                if ( ! $c) {
                    // dateday weekend
                    echo "<tr class=\"gray\"><td> $dname </td>"
                      . "<td colspan=\"4\">Не работал</td></tr>";
                    continue;
                }

                echo "<tr>
                        <td> $dname </td>
                        <td> ".date('H:i:s', $dayLogArr['day_begin'])." </td>
                        <td>  ".date('H:i:s', $dayLogArr['day_begin'] + $dayLogArr['dey_length'])." </td>
                        <td>  </td>
                        <td><a onclick=\"return false;\" class=\"subreport\">[<sp>+</sp>{$c}]</a></td>
                    </tr>";
                ?>
                <tr class="subreport">
                    <td colspan="5" class="hidden">
                        <table class="grid">
                            <tr class="head">
                                <td>Время</td>
                                <td>Событие</td>
                                <td>Инициатор</td>
                                <td>Длительность</td>
                                <td>Причина</td>
                            </tr>
                            <?php foreach ($dayLog as $agentLog) { ?>
                                <tr>
                                    <td><?php echo $agentLog->datetime->format('H:i:s'); ?></td>
                                    <td> <?php echo $agentLog->action1; ?> <!-- <tt class="field-inf"><?php echo $agentLog->action; ?></tt> --></td>
                                    <td> Оператор </td>
                                    <td><?php echo $agentLog->action2; ?></td>
                                    <td> </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php return; ?>



<div class="clear clear_fix">
    <table class="grid" style="width: 900px;">
        <thead>
            <tr>
                <td class="head"  style="width: 150px;">Дата - Время</td>
                <td class="head"  style="width: 150px;">Рабочее место</td>
                <td class="head"  style="">Оператор</td>
                <td class="head"  style="width: 200px;">Действие</td>
                <td class="head"  style="width: 150px;"> </td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->dataResult as $agentLog) { ?>
                <tr>
                    <td><?php echo $agentLog->datetime; ?></td>
                    <td><?php echo $agentLog->agentphone; ?></td>
                    <td><?php echo html(QueueAgent::getOper($agentLog->agentid)); ?></td>
                    <td><?php echo $agentLog->action1; ?></td>
                    <td><?php echo $agentLog->action2; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
