<?php ?>
<script type="text/javascript">
    function showOperatorDelete(id) {
        var $dialog = $('#dialog-operator-delete');
        $dialog.find('input[name=agentid]').val(id);
        $dialog.dialog('open');
    }

    $(document).ready(function() {
        $('#dialog-operator-delete').dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
            resizable: false,
            minWidth: 350,
            minHeight: 70,
            buttons: {
                "OK": function() {
                    $('#dialog-operator-delete').find('form').eq(0).submit();
                },
                "Отмена": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
</script>
<div id="dialog-operator-delete" class="dialog hidden edit-content" title="Удалить оператора">
    <form method="post">
        <input type="hidden" name="action" value="delete" />
        <input type="hidden" name="agentid" value="0" />
        <div> Удалить оператора </div>
    </form>
</div>