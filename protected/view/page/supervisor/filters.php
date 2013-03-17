<?php
/**
 * filters.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
?>
<div class="filters clear_fix">
    <input id="input-section" type="hidden" value="<?php echo $this->getSection(); ?>" />
    <div class="filter fl_l">
        <div class="fl_l" style="padding-top:5px;">
            <a onclick="supervisor.actUpdate(); return false;"><img src="images/update.png" /><!-- Обновить --></a></div>
    </div>
    <div class="filter fl_l">
        <div class="label fl_l" style="margin: 2px 5px 0px 0px;">Динамическое обновление</div>
        <div class="label fl_l"><input id="input-dynamic_update" type="checkbox" <?php echo (App::Config()->supervisor['dynamic_update']) ? 'default="1"' : ''; ?>  /></div>
    </div>
    <div class="filter fl_l">
        <div class="label fl_l" style="margin: 2px 5px 0px 5px;">Интервал </div>
        <div class="label fl_l"><input id="input-update_interval" type="text" autocomplete="off" class="field-number" style="width: 5em;" default="<?php echo (int) App::Config()->supervisor['update_interval']; ?>" /></div>
    </div>
    <div class="clear clear_fix bigblock"> </div>
</div>
