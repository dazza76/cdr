<?php
/* AC: v: */

/**
 * supervisor_queue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */

include 'filters.php';


?>

<div class="filters clear_fix mediumblock of_h">
    <table class="grid" style="width: 1200px;" htable="1">
        <thead >
            <tr height="30px">
                <th align="center">Очередь</th>
                <th align="center" style="width: 100px;">Операторы</th>
                <th align="center" style="width: 100px;">Ожидают</th>
                <th align="center" style="width: 100px;">Дольше всего ожидает</th>
                <th align="center" style="width: 100px;">Обслужено</th>
                <th align="center" style="width: 100px;">Ср. время разговора</th>
                <th align="center" style="width: 100px;">Ср. время ожидание</th>
                <th align="center" style="width: 100px;">Потеряно</th>
                <th align="center" style="width: 100px;">Ср. ожидание потеряных</th>
                <th align="center" style="width: 100px;">SERVICE<br>LEVEL (60)</th>
            </tr>
        </thead>
    </table>
</div>

<div class="clear clear_fix">
    <table id="queuesData" class="grid" style="width: 1200px;" htable="1">
        <thead>
            <tr class="b-head">
                <th > </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
                <th style="width: 100px;"> </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->queuesData as $k=>$row) { ?>
                <tr queueid="<?php echo $k; ?>">
                    <td align="center" ><?php echo html($row['name']); ?></td>
                    <td align="right" queue="count_oper"><span><?php echo html($row['count_oper']); ?></span></td>
                    <td align="right" queue="waiting"><span><?php echo html($row['waiting']); ?></span></td>
                    <td align="right" queue="max_time"><span><?php echo html($row['max_time']); ?></span></td>
                    <td align="right" queue="served"><span><?php echo html($row['served']); ?></span></td>
                    <td align="right" queue="avg_call"><span><?php echo html($row['avg_call']); ?></span> сек.</td>
                    <td align="right" queue="avg_hold"><span><?php echo html($row['avg_hold']); ?></span> сек.</td>
                    <td align="right" queue="lost"><span><?php echo html($row['lost']); ?></span></td>
                    <td align="right" queue="avg_abandon"><span><?php echo html($row['avg_abandon']); ?></span> сек.</td>
                    <td align="right" queue="service"><span><?php echo html($row['service']); ?></span> %</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>