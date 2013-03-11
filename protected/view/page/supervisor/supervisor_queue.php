<?php
/* AC: v: */

/**
 * supervisor_queue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */

include 'filters.php';
?>



<div class="clear clear_fix bigblock">
    <table id="result" class="grid" align="center">
        <thead height="50px">
            <tr>
                <th align="center">Очередь</th>
                <th align="center">Операторы</th>
                <th align="center">Ожидают</th>
                <th align="center">Дольше всего ожидает</th>
                <th align="center">Обслужено</th>
                <th align="center">Ср. время разговора</th>
                <th align="center">Ср. время ожидание</th>
                <th align="center">Потеряно</th>
                <th align="center">Ср. ожидание потеряных</th>
                <th align="center">SERVICE<br>LEVEL (60)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->queuesData as $row) { ?>
                <tr>
                    <td align="center"><?php echo html($row['name']); ?></td>
                    <td align="right"><?php echo html($row['count_oper']); ?></td>
                    <td align="right"><?php echo html($row['waiting']); ?></td>
                    <td align="right"><?php echo html($row['max_time']); ?></td>
                    <td align="right"><?php echo html($row['served']); ?></td>
                    <td align="right"><?php echo html($row['avg_call']); ?> сек.</td>
                    <td align="right"><?php echo html($row['avg_hold']); ?> сек.</td>
                    <td align="right"><?php echo html($row['lost']); ?></td>
                    <td align="right"><?php echo html($row['avg_abandon']); ?> сек.</td>
                    <td align="right"><?php echo html($row['service']); ?> %</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>