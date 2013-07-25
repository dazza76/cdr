<?php
/**
 * schedule.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */
// $agents = $this->agents;
// include VIEWDIR.'page/supervisor/dialog-operators.php';
?>
<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <input name="section" type="hidden" value="schedule" />

        <div class="filter fl_l">
            <div class="labeled">
                <span>
                    <input type="hidden" id="export_type" name="export" value="1" />
                    <a id="button-operators" href="#" class="icon icon-group puinter">Операторы</a>
                </span>
            </div>
        </div>

        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="date" type="text" autocomplete="off" value="<?php echo $this->date->format('d.m.Y'); ?>" class="datepicker" />
            </div>
        </div>

        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>
    </form>
</div>

<?php
$m       = $this->date->format('m');
list($days, $day) = explode(' ',
                            date('t w', strtotime($this->date->format('Y-m-1'))));
$weekday = array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб");
?>


<table class="schedule">
    <thead>
        <tr class="head">
            <td>Оператор</td>
            <?php
            for ($i = 1; $i <= $days; $i ++ && $day ++ ) {
                if ($day == 7)
                    $day = 0;

                echo "<td> {$i}.{$m}<br />  {$weekday[$day]}   </td>";
            }
            ?>
            <td>Ставка</td>
            <td>План</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $date = $this->date->format('Y-m-');
        foreach ($this->agents as $id => $name) {
            echo "<tr agentid=\"{$id}\">";
            echo "<td>{$name}</td>";
            $duration = 0;
            for ($i = 1; $i <= $days; $i ++ && $day ++ ) {
                if ($day == 7)
                    $day = 0;


                if ( ! $data = $this->schedule[$id][$i]) {
                    $data = array('event' => 'off');
                }

                switch ($data['event']) {
                    case 'off':
                        $data['txt']   = "В";
                        break;
                    case 'ill':
                        $data['txt']   = "Б";
                        break;
                    case 'vac':
                        $data['txt']   = "О";
                        break;
                    case 'job':
                        $data['txt']   = substr($data['start'], 0, -3) . "<br />{$data['duration']} ч.";
                        $duration += $data['duration'];
                        break;
                    default :
                        $data['event'] = "err";
                        $data['txt']   = "Err";
                        break;
                }

                echo "<td date=\"{$date}{$i}\" event=\"{$data['event']}\" >  {$data['txt']}  </td>";
            }

            echo "<td>0</td>";
            echo "<td>{$duration}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>