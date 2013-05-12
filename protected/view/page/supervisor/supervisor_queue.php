<?php
/* AC: v: */

/**
 * supervisor_queue.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 */

// include 'filters.php';

?>
<script type="text/javascript">
    var pageOptions = {
        section: 'queue'
    };
</script>
<div class="clear clear_fix">
    <table id="queuesData" class="grid" style="width: 1200px;">
        <thead>
            <tr height="30px">
                <td class="head"  align="center">Очередь</td>
                <td class="head"  align="center" style="width: 100px;">Операторы</td>
                <td class="head"  align="center" style="width: 100px;">Ожидают</td>
                <td class="head"  align="center" style="width: 100px;">Дольше всего ожидает</td>
                <td class="head"  align="center" style="width: 100px;">Обслужено</td>
                <td class="head"  align="center" style="width: 100px;">Ср. время разговора</td>
                <td class="head"  align="center" style="width: 100px;">Ср. время ожидание</td>
                <td class="head"  align="center" style="width: 100px;">Потеряно</td>
                <td class="head"  align="center" style="width: 100px;">Ср. ожидание потеряных</td>
                <td class="head"  align="center" style="width: 100px;">SERVICE<br>LEVEL (60)</td>
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