<?php

include 'dialog-queue.php';

?>

<div class="filters clear_fix">
    <div class="clear_fix bigblock of_h" style="width: 800px">
        <div class="fl_l" style="padding-right: 15px;">
            Всего: <?php echo @count($this->dataQueues); ?>
        </div>
        <div class="fl_r"><a onclick="$('#dialog-queue-add').dialog('open');" class="icon icon-add">добавить</a></div>
        <div class="pg-pages fl_r">  </div>
    </div>
</div>



<div id="operator_list" class="clear clear_fix">
    <table class="grid" style="width: 800px;">
        <thead>
            <tr>
                <td class="head"  style="width: 150px;">Имя очереди</td>
                <td class="head"  style="width: 150px;">Интерфейс</td>
                <td class="head"  style="width: 70px;" >Пенальти</td>
                <td class="head"  >uniqueid</td>
                <td class="head"  style="width: 70px;" >Paused</td>
                <td class="head"  style="width: 70px;">Изменить</td>
                <td class="head"  style="width: 70px;">Удалить</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->dataQueues as $queue) { ?>
            <tr>
                <td><?php echo html($queue['queue_name']); ?></td>
                <td><?php echo html($queue['interface']); ?></td>
                <td align="center"><?php echo html($queue['penalty']); ?></td>
                <td ><?php echo html($queue['uniqueid']); ?></td>
                <td align="center"><?php echo $queue['paused']; ?></td>
                <td class="image-link"><a href="?section=queue&uniqueid=<?php echo html($queue['uniqueid']); ?>" class="icon icon-edit"></a></td>
                <td class="image-link"><a  onclick="showQueueDelete('<?php echo html($queue['uniqueid']); ?>');" class="icon icon-delete"></a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div id="operator_list" class="clear clear_fix">
    <table class="grid" >
        <thead>
            <tr>
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
        </tbody>
    </table>
</div>