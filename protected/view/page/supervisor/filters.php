<?php
/**
 * filters.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<script type="text/javascript">
    $(document).ready(function() {
        supervisor.update = <?php echo (App::Config()->supervisor['dynamic_update']) ? 1 : 0; ?>;
        supervisor.interval = <?php echo (int) App::Config()->supervisor['update_interval']; ?>;
        supervisor.init();
    });
</script>


<div class="filters clear_fix">
    <div class="filter fl_l">
        <div class="fl_l" style="padding-top:5px;">
            <a href="#"><img src="images/update.png" /><!-- Обновить --></a></div>
    </div>
    <div class="filter fl_l">
        <div class="label fl_l" style="margin: 2px 5px 0px 0px;">Динамическое обновление</div>
        <div class="label fl_l"><input id="input-dynamic_update" type="checkbox"  /></div>
    </div>
    <div class="filter fl_l">
        <div class="label fl_l" style="margin: 2px 5px 0px 5px;">Интервал </div>
        <div class="label fl_l"><input id="input-update_interval" type="text" autocomplete="off" maxlength="5" class="field-number" style="width: 5em;" value="1000" /></div>
    </div>
    <div class="clear clear_fix bigblock"> </div>
</div>
