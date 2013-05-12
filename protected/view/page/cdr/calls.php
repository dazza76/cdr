<?php
/**
 * calls.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/* @var $this CdrController */
/* @var $row Cdr */
?>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y H:i'); ?>" class="datetimepicker" >
            </div>
        </div>
        <div class="filter fl_l sep">
            <div class="label">Оператор</div>
            <div class="labeled">
                <select name="oper" size="1"  default="<?php echo $this->oper; ?>">
                    <?php echo QueueAgent::showOperslist(); ?>
                </select>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Телефон</div>
            <div class="labeled">
                <input name="src" type="text" placeholder="Источник" autocomplete="off" style="width: 8em;" value="<?php echo html($this->src); ?>">
                —
                <input name="dst" type="text" placeholder="Назначение" autocomplete="off" style="width: 8em;" value="<?php echo html($this->dst); ?>">
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Звонки</div>
            <div class="labeled">
                <select name="coming" size="1"  default="<?php echo $this->coming; ?>">
                    <option value="" selected="selected">все звонки</option>
                    <option value="1">входящий</option>
                    <option value="2">исходящий</option>
                </select>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Комментарий</div>
            <div class="labeled">
                <input name="comment" type="text" placeholder="Комментарий" autocomplete="off" style="width: 10em;" value="<?php echo html($this->comment); ?>">
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">мобильные</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="mob" type="checkbox" value="1" <?php if ($this->mob) echo "default=\"1\""; ?> />
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">VIP</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="vip" type="checkbox" value="1" <?php if ($this->vip) echo "default=\"1\""; ?> />
            </div>
        </div>


        <div class="filter fl_l sep">
            <div class="label">Показать</div>
            <div class="labeled">
                <select name="limit" size="1"  default="<?php echo $this->limit; ?>">
                    <option value="" selected="selected">30</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>
        </div>

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>

        <input type="hidden" name="sort" value="<?php echo $this->sort; ?>" />
        <input type="hidden" name="desc" value="<?php echo $this->desc; ?>" />
        <input type="hidden" name="offset" value="<?php echo $this->offset; ?>" />
    </form>
</div>

<div class="filters clear_fix bigblock of_h">
    <div class="fl_l" style="padding-right: 15px;">
        Найдено: <?php echo $this->count ?>
    </div>
    <div class="pg-pages fl_r">
        <?php
        echo Utils::pagenator($this->count, $this->offset, $this->limit, $this->getFilters());
        ?>
    </div>
</div>


<div class="clear clear_fix">
    <table class="grid" style="width: 1100px;">
        <thead>
            <tr>
                <td class="head"  style="width: 60px;"  >Напр.</td>
                <td class="head"  style="width: 150px;" <?php echo Utils::sortable("calldate", $this->sort, $this->desc); ?> >Дата</td>
                <td class="head"  style="width: 150px;" <?php echo Utils::sortable("src", $this->sort, $this->desc); ?> >Источник</td>
                <td class="head"  style="width: 150px;" <?php echo Utils::sortable("dst", $this->sort, $this->desc); ?> >Назначение</td>
                <td class="head"  style="width: 150px;" >Оператор</td>
                <td class="head"  style="width: 135px;" >Запись</td>
                <td class="head"  style="width: 70px;" <?php echo Utils::sortable("duration", $this->sort, $this->desc); ?> >Время</td>
                <td class="head"  style="" <?php echo Utils::sortable("comment", $this->sort, $this->desc); ?> >Комментарий</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->rows as $row) {  ?>
                <tr callid="<?php echo $row->id; ?>">
                    <td class="coming-img"><div class="coming_<?php echo $row->getComing(); ?>"></div></td>
                    <td><?php echo $row->calldate->format('d.m.Y H:i:s') ?></td>
                    <td><?php echo html($row->src); ?></td>
                    <td><?php echo html($row->getDst()); ?></td>
                    <td><?php echo QueueAgent::getOper($row->getOperatorCode()); ?></td>
                    <td>
                        <div class="fl_l">
                            <a href="<?php echo $row->getFile(); ?>" target="_blank" ><img src="images/b_save.png" /></a>
                        </div>
                        <div class="player-button fl_l icon-play" style="margin-left: 5px;">
                            <input type="hidden" value="<?php echo $row->getFile(); ?>" />
                        </div>
                        <div class="player-slider fl_l"></div>
                    </td>
                    <td><?php echo $row->getTime(); ?></td>
                    <td class="comment grid-edit"><span><?php echo html($row->comment); ?></span></td>
                </tr>
                <?php  } ?>
        </tbody>
    </table>
</div>
<?php
include 'comment.php';
include 'jplayer.php';
?>