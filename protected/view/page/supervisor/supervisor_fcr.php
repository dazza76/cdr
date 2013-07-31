<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this QueueController */
?>


<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y'); ?>" class="datepicker" >
            </div>
        </div>
<!--         <div class="filter fl_l sep">
            <div class="label">Оператор</div>
            <div class="labeled">
                <select name="oper" size="1"  default="<?php echo $this->oper; ?>">
                    <?php echo QueueAgent::showOperslist(); ?>
                </select>
            </div>
        </div> -->

<!--         <div class="filter fl_l sep">
            <div class="label">Статус</div>
            <div class="labeled">
                <select name="status" default="<?php echo $this->status; ?>">
                    <option value="">Любой</option>
                    <option value="ABANDON">Потерян</option>
                    <option value="COMPLETEAGENT">Завершен оператором</option>
                    <option value="COMPLETECALLER">Завершен клиентом</option>
                    <option value="TRANSFER">Переведен</option>
                </select>
            </div>
        </div>
 -->
        <div class="filter fl_l sep">
            <div class="label">Очередь</div>
            <div class="labeled">
                <?php echo Queue::showMultiple("queue[]", $this->queue); ?>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">мобильные</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="mob" type="checkbox" value="1" <?php if ($this->mob) echo "default=\"1\""; ?> />
            </div>
        </div>


<!--         <div class="filter fl_l sep">
            <div class="label">VIP</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="vip" type="checkbox" value="1" <?php if ($this->vip) echo "default=\"1\""; ?> />
            </div>
        </div> -->

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>

<!--         <div class="filter fl_r">
            <div class="labeled" style="margin-top: 24px;">
                <span>
                    <input type="hidden" id="export_type" name="export" value="1" />
                    <a id="button-export" href="" class="icon icon_excel">Экспорт</a>
                </span>
            </div>
        </div> -->

        <input type="hidden" name="section" value="fcr" />
        <input type="hidden" name="sort" value="<?php echo $this->sort; ?>" />
        <input type="hidden" name="desc" value="<?php echo $this->desc; ?>" />
        <input type="hidden" name="offset" value="<?php echo $this->offset; ?>" />
    </form>
</div>


<div class="filters clear clear_fix bigblock of_h">
    <div class="fl_l" style="padding-right: 15px;">
        <!-- Найдено: <?php echo $this->count ?> -->
    </div>
    <div class="pg-pages fl_r">
        <?php
        //echo Utils::pagenator($this->count, $this->offset, $this->limit, $this->getFilters());
        ?>
    </div>
</div>




<div class="clear clear_fix">
    <div style="margin-bottom: 5px"><b>Таблица 1.</b> В первой таблице мы собираем все вызовы которые встречаются более чем 1 раз. Первый их звонок был не отвеченным.</div>
    <table class="grid">
        <thead>
            <tr>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('timestamp', $this->sort, $this->desc); */ ?> >Дата - Время</td>
                <td class="head sortable"  style="width: 250px;" <?php /* echo Utils::sortable('callerId', $this->sort, $this->desc); */ ?> >Входящий номер</td>
                <td class="head sortable"  style="width: 170px;" <?php /* echo Utils::sortable('callId', $this->sort, $this->desc); */ ?> >Назначение</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('memberId', $this->sort, $this->desc); */ ?> >Оператор</td>
                <td class="head sortable"  style="width: 170px;" <?php /* echo Utils::sortable('status', $this->sort, $this->desc); */ ?> >Действие</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('holdtime', $this->sort, $this->desc); */ ?> >Ожидание в очереди</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('ringtime', $this->sort, $this->desc); */ ?> >Поднятие трубки</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('callduration', $this->sort, $this->desc); */ ?> >Длительность</td>
                <td class="head sortable"  style="width: 70px;"  <?php /* echo Utils::sortable('originalPosition', $this->sort, $this->desc); */ ?> >Вошел</td>
                <td class="head sortable"  style="width: 70px;"  <?php /* echo Utils::sortable('position', $this->sort, $this->desc); */ ?> >Вышел</td>
                <td class="head sortable"  style=""              <?php /* echo Utils::sortable('queue', $this->sort, $this->desc); */ ?> >Очередь</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->rows_fcr as $rows) {
                if(count($rows) <= 1) {
                    continue;
                }
                $_first = true;
                if ($rows[0]->status != 'ABANDON') {
                    continue;
                }
                foreach($rows as $row) {
                    /* @var $row CallStatus */
                    if ($this->vip && ( ! $row->priorityId)) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td><?php echo ($_first) ? $row->timestamp->format('d.m.Y H:i:s') : "# <span style=\"padding-left:55px;\">{$row->timestamp->format('H:i:s')}</span>"; ?></td>
                        <td><?php echo ($_first) ? $row->getCaller() : ' - '; ?></td>
                        <td><?php echo $row->dst; ?></td>
                        <td><?php echo $row->getOper(); ?></td>
                        <td><?php echo $row->getStatus(); ?></td>
                        <td><?php echo Utils::time($row->holdtime); ?></td>
                        <td><?php echo $row->ringtime; ?></td>
                        <td><?php echo Utils::time($row->callduration); ?></td>
                        <td><?php echo $row->originalPosition; ?></td>
                        <td><?php echo $row->position; ?></td>
                        <td><?php echo html(Queue::getQueue($row->queue)); ?></td>
                    </tr>
                    <?php
                    $_first = false;
                }
            }
            ?>
        </tbody>
    </table>


    <div style="margin-top: 25px; margin-bottom: 5px;"><b>Таблица 2.</b> Во второй таблице мы собираем все вызовы которые встречаются более чем 1 раз. Первый звонок был отвеченным.</div>
    <table class="grid">
        <thead>
            <tr>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('timestamp', $this->sort, $this->desc); */ ?> >Дата - Время</td>
                <td class="head sortable"  style="width: 250px;" <?php /* echo Utils::sortable('callerId', $this->sort, $this->desc); */ ?> >Входящий номер</td>
                <td class="head sortable"  style="width: 170px;" <?php /* echo Utils::sortable('callId', $this->sort, $this->desc); */ ?> >Назначение</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('memberId', $this->sort, $this->desc); */ ?> >Оператор</td>
                <td class="head sortable"  style="width: 170px;" <?php /* echo Utils::sortable('status', $this->sort, $this->desc); */ ?> >Действие</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('holdtime', $this->sort, $this->desc); */ ?> >Ожидание в очереди</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('ringtime', $this->sort, $this->desc); */ ?> >Поднятие трубки</td>
                <td class="head sortable"  style="width: 150px;" <?php /* echo Utils::sortable('callduration', $this->sort, $this->desc); */ ?> >Длительность</td>
                <td class="head sortable"  style="width: 70px;"  <?php /* echo Utils::sortable('originalPosition', $this->sort, $this->desc); */ ?> >Вошел</td>
                <td class="head sortable"  style="width: 70px;"  <?php /* echo Utils::sortable('position', $this->sort, $this->desc); */ ?> >Вышел</td>
                <td class="head sortable"  style=""              <?php /* echo Utils::sortable('queue', $this->sort, $this->desc); */ ?> >Очередь</td>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->rows_fcr as $rows) {
                if(count($rows) <= 1) {
                    continue;
                }
                $_first = true;
                if ($rows[0]->status == 'ABANDON') {
                    continue;
                }
                foreach($rows as $row) {
                    /* @var $row CallStatus */
                    if ($this->vip && ( ! $row->priorityId)) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td><?php echo ($_first) ? $row->timestamp->format('d.m.Y H:i:s') : "# <span style=\"padding-left:55px;\">{$row->timestamp->format('H:i:s')}</span>"; ?></td>
                        <td><?php echo ($_first) ? $row->getCaller() : ' - '; ?></td>
                        <td><?php echo $row->dst; ?></td>
                        <td><?php echo $row->getOper(); ?></td>
                        <td><?php echo $row->getStatus(); ?></td>
                        <td><?php echo Utils::time($row->holdtime); ?></td>
                        <td><?php echo $row->ringtime; ?></td>
                        <td><?php echo Utils::time($row->callduration); ?></td>
                        <td><?php echo $row->originalPosition; ?></td>
                        <td><?php echo $row->position; ?></td>
                        <td><?php echo html(Queue::getQueue($row->queue)); ?></td>
                    </tr>
                    <?php
                    $_first = false;
                }
            }
            ?>
        </tbody>
    </table>
</div>

