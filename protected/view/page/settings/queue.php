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
                <td>name</td>
                <td>musiconhold</td>
                <td>announce</td>
                <td>context</td>
                <td>timeout</td>
                <td>monitor_join</td>
                <td>monitor_format</td>
                <td>queue_youarenext</td>
                <td>queue_thereare</td>
                <td>queue_callswaiting</td>
                <td>queue_holdtime</td>
                <td>queue_minutes</td>
                <td>queue_seconds</td>
                <td>queue_lessthan</td>
                <td>queue_thankyou</td>
                <td>queue_reporthold</td>
                <td>announce_frequency</td>
                <td>announce_round_seconds</td>
                <td>announce_holdtime</td>
                <td>retry</td>
                <td>wrapuptime</td>
                <td>maxlen</td>
                <td>servicelevel</td>
                <td>strategy</td>
                <td>joinempty</td>
                <td>leavewhenempty</td>
                <td>eventmemberstatus</td>
                <td>eventwhencalled</td>
                <td>reportholdtime</td>
                <td>memberdelay</td>
                <td>weight</td>
                <td>timeoutrestart</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>