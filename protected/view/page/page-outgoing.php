<?php
/**
 * cdr.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this TimemanController */
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
            <div class="label">Телефон</div>
            <div class="labeled">
                <input name="src" type="text" placeholder="Источник" autocomplete="off" style="width: 8em;" value="<?php echo html($this->src); ?>">
                —
                <input name="dst" type="text" placeholder="Назначение" autocomplete="off" style="width: 8em;" value="<?php echo html($this->dst); ?>">
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Статус</div>
            <div class="labeled">
                <select name="disposition" size="1"  default="<?php echo $this->disposition; ?>">
                    <option value="">Любой</option>
                    <option value="BUSY">Занято</option>
                    <option value="NO ANSWER">Нет ответа</option>
                    <option value="ANSWERED">Принят</option>
                </select>
            </div>
        </div>

<!--         <div class="filter fl_l sep">
            <div class="label">Показать</div>
            <div class="labeled">
                <select name="limit" size="1"  default="<?php echo $this->limit; ?>">
                    <option value="" selected="selected">30</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>
        </div> -->

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

<div class="clear clear_fix bigblock">
    <table id="result" class="grid" style="width: 900px;"  >
            <thead>
                <tr>
                    <td class="head" style="width: 135px;" >Дата - Время</td>
                    <td class="head" style="" >Источник</td>
                    <td class="head" style="width: 135px;" >Назначение</td>
                    <td class="head" style="width: 135px;" >Ожидание</td>
                    <td class="head" style="width: 135px;" >Разговор</td>
                    <td class="head" style="width: 135px;" >Результат</td>
                </tr>
            </thead>
            <tbody>
<?php
while($row = $this->dataResult->fetchAssoc()) {
    $count = count($row);
    $html_out = "<tr>";
    $html_out .= '<td>'.$row[calldate].'</td>';
    $html_out .= '<td>'.QueueAgent::getOper($row[userfield]).'('.$row[src].')</td>';
    if(strlen($row[dst])>4)
            $html_out .= '<td>'.substr($row[dst],1).'</td>';
        else
        $html_out .= '<td>'.$row[dst].'</td>';
    $html_out .= '<td>'.Utils::time($row[duration]-$row[billsec]).'</td>';
    $html_out .= '<td>'.Utils::time($row[billsec]).'</td>';
    switch($row[disposition])
    {
        case 'BUSY':
            $html_out .= '<td>Занято</td>';
        break;
        case 'ANSWERED':
            $html_out .= '<td>Принят</td>';
        break;
        case 'NO ANSWER':
            $html_out .= '<td>Нет ответа</td>';
        break;
        default:
            $html_out .= '<td>Н/Д</td>';
        break;
    }
    $html_out .= "</tr>";
    echo $html_out;
}
?>
</tbody>
</table>
</div>

