<?php

$fields = array(
  'musiconhold' => "varchar;128",
  'announce' => "varchar;128",
  'context' => "varchar;128",
  'timeout' => "int;11",
  'monitor_join' => "int;1",
  'monitor_format' => "varchar;128",
  'queue_youarenext' => "varchar;128",
  'queue_thereare' => "varchar;128",
  'queue_callswaiting' => "varchar;128",
  'queue_holdtime' => "varchar;128",
  'queue_minutes' => "varchar;128",
  'queue_seconds' => "varchar;128",
  'queue_lessthan' => "varchar;128",
  'queue_thankyou' => "varchar;128",
  'queue_reporthold' => "varchar;128",
  'announce_frequency' => "int;11",
  'announce_round_seconds' => "int;11",
  'announce_holdtime' => "varchar;128",
  'retry' => "int;11",
  'wrapuptime' => "int;11",
  'maxlen' => "int;11",
  'servicelevel' => "int;11",
  'strategy' => "varchar;128",
  'joinempty' => "varchar;128",
  'leavewhenempty' => "varchar;128",
  'eventmemberstatus' => "int;1",
  'eventwhencalled' => "int;1",
  'reportholdtime' => "int;1",
  'memberdelay' => "int;11",
  'weight' => "int;11",
  'timeoutrestart' => "int;1",
  );

?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#form-queue-edit").submit(function(){
            var $form = $(this);
            var fields = {};
            $("#form-queue-edit input").each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                fields[name] = value;
            });
            console.log(fields);

            if (!fields['name']) {
                $form.find("input[name='name']").addClass('field-sucfail');
                return false;
            } else {
                $form.find("input[name='name']").removeClass('field-sucfail');
            }

            return true;
        });
    });
</script>

<div class="ui-widget-content edit-content" style="margin-top: 10px; width: 550px;" >
    <div class="ui-widget-header" style="padding: 4px 0; text-align: center;">Изменить очередь</div>
    <form method="post" id="form-queue-edit">
        <input type="hidden" name="action" value="create" />

        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span class="field-required">*</span>name:</div>
            <div class="labeled fl_l"><input type="text" style="width: 200px;" name="name" value="" maxlength="128" /></div>
        </div>

<?php
foreach($fields as $name=>$param) {
    list($type, $length) = explode(';', $param);
    $cl = ($type == "int") ? "number" : "";
    ?>
        <div class="clear clear_fix miniblock">
            <div class="label fl_l ta_r"><span style="color: rgb(141, 153, 185);"><?php echo $type."($length) "; ?></span><?php echo $name; ?>:</div>
            <div class="labeled fl_l"><input type="text" class="<?php echo $cl; ?>" style="width: 200px;" name="<?php echo $name; ?>" value="" maxlength="<?php echo $length; ?>" /></div>
        </div>
<?php } ?>
        <div class="clear clear_fix bigblock">
            <div class="label fl_l ta_r" style="width: 250px;">-</div>
            <div class="labeled fl_l" style="width: 220px; margin-left: 5px;">
                <button class="button">Сохранить</button>
            </div>
        </div>
        <div class="clear clear_fix bigblock"></div>
    </form>
</div>