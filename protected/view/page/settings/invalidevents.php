<?php
include 'dialog-event.php';
?>
<div class="filters clear_fix">
    <div class="clear_fix bigblock of_h" style="width: 800px">
        <div class="fl_l" style="padding-right: 15px;">
            <!-- Всего операторов:  -->
        </div>
        <div class="fl_r" style="margin-right:15px;"><a onclick="$('#dialog-event-add').dialog('open');" class="icon icon-add abut">добавить</a></div>
        <div class="pg-pages fl_r">  </div>
    </div>
</div>

<div id="events_list" class="clear clear_fix">
    <table class="grid" style="width: 800px;">
        <thead>
            <tr >
                <td class="head" > Причина срабатывания</td>
                <td class="head"  style="width: 100px;"> Имя модуля</td>
                <td class="head"  style="width: 70px;" > Значение</td>
                <td class="head"  style="width: 70px;" > Регистрация события</td>
                <td class="head"  style="width: 70px;" > Немедленно уведомлять</td>
                <td class="head"  style="width: 70px;" > - </td>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $this->dataResult->fetchAssoc()) {?>
            <tr>
                <td><?php echo html($row['name']); ?></td>
                <td><?php echo html($row['filename']); ?></td>
                <td align="center"><?php echo $row['value']; ?></td>
                <td><?php echo (($row['enabled'] == 'yes') ? 'Вкл.' : 'Выкл.'); ?></td>
                <td><?php echo (($row['urgent'] == 'yes') ? 'Вкл.' : 'Выкл.'); ?></td>
                <td>
                    <a  class="icon icon-edit pointer" style="margin-right: 5px;"></a>
                    <a onclick="showEventDelete(<?php echo $row['id']; ?>);" class="icon icon-delete pointer"></a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>