<?php
/* AC: v: */

/**
 * dialog-operator-add.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#dialog-operator-add').dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
             resizable: false,
            minWidth: 550,
            minHeight: 70,
            buttons: {
                "OK": function () {
                    $(this).dialog("close");
                },
                                "Отмена": function () {
                    $(this).dialog("close");
                }
            }
        });
    });
</script>
<div id="dialog-operator-add" class="dialog hidden edit-content" title="Добавить оператора">
    <div class="clear clear_fix bigblock">
        <div class="label fl_l ta_r" style="width: 250px;">ФИО:</div>
        <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="" value="" /></div>
    </div>
    <div class="clear clear_fix">
        <div class="label fl_l ta_r" style="width: 250px;">Номер оператора:</div>
        <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="" value="" /></div>
    </div>
    <div class="clear clear_fix">
        <div class="label fl_l ta_r" style="width: 250px;">Телефон, на котором работает:</div>
        <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="" value="" /></div>
    </div>
    <div class="clear clear_fix">
        <div class="label fl_l ta_r" style="width: 250px;">Список очередей пенальти 1:</div>
        <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="" value="" /></div>
    </div>
    <div class="clear clear_fix">
        <div class="label fl_l ta_r" style="width: 250px;">Пенальти в очередях 1:</div>
        <div class="labeled fl_l" style="width: 220px; margin-left: 5px;"><input type="text" name="" value="" /></div>
    </div>
</div>
