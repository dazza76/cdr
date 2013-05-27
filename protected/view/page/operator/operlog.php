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
    <table class="grid" style="width: 900px;">
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
            <?php foreach ($this->dataResult as $agentLog) { ?>
            <tr>
                <td><?php echo $agentLog->datetime; ?></td>
                <td><?php echo $agentLog->agentphone; ?></td>
                <td><?php echo html(QueueAgent::getOper($agentLog->agentid) ); ?></td>
                <td><?php echo $agentLog->action1; ?></td>
                <td><a onclick="return false;" class="subreport">[+11]</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php return; ?>

    <table class="grid" style="width: 900px;">
        <thead>
            <tr>
                <td class="head">Время</td>
                <td class="head">Событие</td>
                <td class="head">Инициатор</td>
                <td class="head">Длительность</td>
                <td class="head">Причина</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->dataResult as $agentLog) { ?>
            <tr>
                <td><?php echo $agentLog->datetime; ?></td>
                <td><?php echo $agentLog->agentphone; ?></td>
                <td><?php echo html(QueueAgent::getOper($agentLog->agentid) ); ?></td>
                <td><?php echo $agentLog->action1; ?></td>
                <td><a onclick="return false;" class="subreport">[+11]</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>







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
                <td><?php echo html(QueueAgent::getOper($agentLog->agentid) ); ?></td>
                <td><?php echo $agentLog->action1; ?></td>
                <td><?php echo $agentLog->action2; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
