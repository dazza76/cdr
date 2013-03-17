<?php

include 'dialog-queue.php';

?>

<div class="filters clear_fix">
    <div class="clear_fix bigblock of_h" style="width: 800px">
        <div class="fl_l" style="padding-right: 15px;">
            Всего: 1
        </div>
        <div class="fl_r"><a onclick="$('#dialog-queue-add').dialog('open');" class="icon icon-add">добавить</a></div>
        <div class="pg-pages fl_r">  </div>
    </div>
</div>

<div class="filters clear_fix mediumblock of_h">
    <table class="grid">
        <thead>
            <tr>
                <th>Имя очереди</th>
                <th>Интерфейс</th>
                <th>Пенальти</th>
                <th>uniqueid</th>
                <th>Paused</th>
                <th>Изменить</th>
                <th>Удалить</th>
            </tr>
        </thead>
    </table>
</div>


<div id="operator_list" class="clear clear_fix">
    <table class="grid" style="width: 800px;">
        <thead>
            <tr class="b-head">
                <th style="width: 150px;"> </th>
                <th style="width: 150px;"> </th>
                <th style="width: 70px;" > </th>
                <th > </th>
                <th style="width: 70px;" > </th>
                <th style="width: 70px;"> </th>
                <th style="width: 70px;"> </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>incoming_rt</td>
                <td>Вадим (SIP/20)</td>
                <td align="center">5</td>
                <td >fkg7HM23Qy</td>
                <td align="center">1</td>
                <td class="image-link"><a href="?section=queue&id=<?php echo 1; ?>" class="icon icon-edit"></a></td>
                <td class="image-link"><a  onclick="showOperatorDelete(<?php echo 1; ?>);" class="icon icon-delete"></a></td>
            </tr>
        </tbody>
    </table>
</div>