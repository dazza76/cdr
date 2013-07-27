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
            <tr>
                <td>Превышение длительности подачи звонка</td>
                <td>ringtime_once</td>
                <td align="center">10</td>
                <td>Вкл.</td>
                <td>Выкл.</td>
                <td>
                    <a href="?section=invalidevents&amp;name=incoming_rt" class="icon icon-edit pointer" style="margin-right: 5px;"></a>
                    <a onclick="showEventDelete('incoming_rt');" class="icon icon-delete pointer"></a>
                </td>
            </tr>
            <tr>
                <td>Превышение допустимого времени ожидания</td>
                <td >holdtime_once</td>
                <td align="center">120</td>
                <td>Вкл.</td>
                <td>Выкл.</td>
                <td>
                    <a href="?section=invalidevents&amp;name=incoming_rt" class="icon icon-edit pointer" style="margin-right: 5px;"></a>
                    <a onclick="showEventDelete('incoming_rt');" class="icon icon-delete pointer"></a>
                </td>
            </tr>

        </tbody>
    </table>
</div>