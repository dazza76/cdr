<?php

include 'dialog-queue.php';

?>

<div class="filters clear_fix">
    <div class="clear_fix bigblock of_h">
        <div class="fl_l" style="padding-right: 15px;">
            Всего: <?php echo @count($this->dataQueues); ?>
        </div>
        <div class="fl_r"><a href="?section=queue&tab=create" class="icon icon-add pointer">добавить</a></div>
        <div class="pg-pages fl_r">  </div>
    </div>
</div>


<div id="operator_list" class="clear clear_fix">
    <table class="grid" >
        <thead>
            <tr>
                <td class="head" ><div style="width: 52px;">-</div></td>
                <td class="head" >name</td>
                <td class="head" >musiconhold</td>
                <td class="head" >announce</td>
                <td class="head" >context</td>
                <td class="head" >timeout</td>
                <td class="head" >monitor_join</td>
                <td class="head" >monitor_format</td>
                <td class="head" >queue_youarenext</td>
                <td class="head" >queue_thereare</td>
                <td class="head" >queue_callswaiting</td>
                <td class="head" >queue_holdtime</td>
                <td class="head" >queue_minutes</td>
                <td class="head" >queue_seconds</td>
                <td class="head" >queue_lessthan</td>
                <td class="head" >queue_thankyou</td>
                <td class="head" >queue_reporthold</td>
                <td class="head" >announce_frequency</td>
                <td class="head" >announce_round_seconds</td>
                <td class="head" >announce_holdtime</td>
                <td class="head" >retry</td>
                <td class="head" >wrapuptime</td>
                <td class="head" >maxlen</td>
                <td class="head" >servicelevel</td>
                <td class="head" >strategy</td>
                <td class="head" >joinempty</td>
                <td class="head" >leavewhenempty</td>
                <td class="head" >eventmemberstatus</td>
                <td class="head" >eventwhencalled</td>
                <td class="head" >reportholdtime</td>
                <td class="head" >memberdelay</td>
                <td class="head" >weight</td>
                <td class="head" >timeoutrestart</td>
            </tr>
        </thead>

        <tbody>
            <?php foreach($this->dataQueues as $queue) { ?>
            <tr>
                <td>
                    <a href="?section=queue&name=<?php echo html($queue['name']); ?>" class="icon icon-edit pointer" style="margin-right: 5px;"></a>
                    <a onclick="showQueueDelete('<?php echo html($queue['name']); ?>');" class="icon icon-delete pointer"></a>
                </td>
                <td><?php echo html($queue['name']); ?></td>
                <td><?php echo html($queue['musiconhold']); ?></td>
                <td><?php echo html($queue['announce']); ?></td>
                <td><?php echo html($queue['context']); ?></td>
                <td><?php echo html($queue['timeout']); ?></td>
                <td><?php echo html($queue['monitor_join']); ?></td>
                <td><?php echo html($queue['monitor_format']); ?></td>
                <td><?php echo html($queue['queue_youarenext']); ?></td>
                <td><?php echo html($queue['queue_thereare']); ?></td>
                <td><?php echo html($queue['queue_callswaiting']); ?></td>
                <td><?php echo html($queue['queue_holdtime']); ?></td>
                <td><?php echo html($queue['queue_minutes']); ?></td>
                <td><?php echo html($queue['queue_seconds']); ?></td>
                <td><?php echo html($queue['queue_lessthan']); ?></td>
                <td><?php echo html($queue['queue_thankyou']); ?></td>
                <td><?php echo html($queue['queue_reporthold']); ?></td>
                <td><?php echo html($queue['announce_frequency']); ?></td>
                <td><?php echo html($queue['announce_round_seconds']); ?></td>
                <td><?php echo html($queue['announce_holdtime']); ?></td>
                <td><?php echo html($queue['retry']); ?></td>
                <td><?php echo html($queue['wrapuptime']); ?></td>
                <td><?php echo html($queue['maxlen']); ?></td>
                <td><?php echo html($queue['servicelevel']); ?></td>
                <td><?php echo html($queue['strategy']); ?></td>
                <td><?php echo html($queue['joinempty']); ?></td>
                <td><?php echo html($queue['leavewhenempty']); ?></td>
                <td><?php echo html($queue['eventmemberstatus']); ?></td>
                <td><?php echo html($queue['eventwhencalled']); ?></td>
                <td><?php echo html($queue['reportholdtime']); ?></td>
                <td><?php echo html($queue['memberdelay']); ?></td>
                <td><?php echo html($queue['weight']); ?></td>
                <td><?php echo html($queue['timeoutrestart']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>