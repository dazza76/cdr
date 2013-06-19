<?php
/* AC: v: */

/**
 * dialog-operator-add.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

$agents = QueueAgent::getQueueAgents();
?>
<script type="text/javascript">
    $(document).ready(function() {
        var $dialog = $('#dialog-operators');
        $dialog.dialog({
            disabled: false,
            autoOpen: false,
            modal: true,
            closeOnEscape: false,
            resizable: false,
            width: 350,
            height: 350,
            buttons: {
                "Закрыть": function() {
                    // for (var i in fields) {
                    //     fields[i].val('');
                    //     fields[i].removeClass("field-sucfail");
                    // }
                    $dialog.dialog('close');
                }
            }
        });

        $("#button-operators").click(function() {
            $dialog.dialog("open");
            return false;
        });

        var agentsScan = <?php echo ACJavaScript::encode(App::Config()->supervisor['agentid']); ?>;
        // agentsScan = agentsScan.split(',');

        $('#dialog-operators input').change(function() {
            var nvar = [];
            $('#dialog-operators input').each(function() {
                if ($(this).attr("checked")) {
                    nvar.push($(this).val());
                }
            });
            $.cookie('supervisor_agentid', nvar.join(','));
            console.log(nvar, this);
        });

    });
</script>
<div id="dialog-operators" class="dialog hidden edit-content" title="Выберети операторов">
    <div style="margin: 0 15px;">
        <?php
        foreach($agents as $key => $value) {
            $ckecked = (in_array($key, App::Config()->supervisor['agentid'])) ? 'checked="checked"' : '';
            ?>
        <div class="clear clear_fix miniblock">
            <div class="fl_l">
                <input type="checkbox" value="<?php echo $key; ?>" <?php echo $ckecked; ?> />
            </div>
            <div class="labeled fl_l" style="margin-top:2px;" ><?php echo html($value); ?></div>
        </div>
        <?php } ?>
    </div>
</div>