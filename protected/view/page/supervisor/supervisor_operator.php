<?php
include 'filters.php';
$max  = 1;
foreach ($this->queueChart as $value) {
    $max += $value;
}
if ($max < 10)
$max = 10;
?>
<script type="text/javascript">
    $(function() {
        var chart;
        $(document).ready(function() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'queueChart',
                    type: 'column'
                },
                title: {
                    align: 'left',
                    text: 'Всего операторов'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    // backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                    // borderColor: '#CCC',
                    // borderWidth: 1,
                    // floating: true,
                    // shadow: true
                    // x: 0,
                    y: 40
                },
                xAxis: {
                    categories: [' ']
                },
                yAxis: {
                    allowDecimals: false,
                    max: <?php echo $max; ?>,
                    title: {
                        text: null
                    }
                },
                tooltip: {
                    formatter: function() {
                        return '' + this.series.name + ': ' + this.y + '';
                    }
                },
                plotOptions: {
                    column: {borderColor: '#303030', shadow: true},
                    series: {
                        animation: {
                            duration: 0
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                        name: 'свободных',
                        data: [<?php echo $this->queueChart['free']; ?>]
                    }, {
                        name: 'разговаривает',
                        data: [<?php echo $this->queueChart['used']; ?>]
                    }, {
                        name: 'обработка',
                        data: [<?php echo $this->queueChart['aftercall']; ?>]
                    }, {
                        name: 'перерыв',
                        data: [<?php echo $this->queueChart['paused']; ?>]
                    }, {
                        name: 'звонит',
                        data: [<?php echo $this->queueChart['ringing']; ?>]
                    }

                ]
            });
        });

    });
</script>

<div class="clear clear_fix bigblock fl_l" style="width: 600px">
    <table id="queueAgent" class="grid">
        <thead height="50px">
            <tr>
                <th align="center" style="width: 250px">Операторы</th>
                <th align="center" style="width: 100px">Статус</th>
                <th align="center" style="width: 80px">Время</th>
                <th align="center">Очередь</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->queueAgents as $queueAgent) { ?>
                <tr agentid="<?php echo $queueAgent->agentid; ?>">
                    <td align="left" agent="name"><?php echo html($queueAgent->name); ?></td>
                    <td align="left" agent="state_phone"><?php echo html($queueAgent->getStatePhone()); ?></td>
                    <td align="right" agent="time"><span tick="time">00:00:00</span></td>
                    <td align="right" agent="queue">1</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="mediumblock fl_l" style="width: 400px; margin-left: 25px;">
    <div id="highcharts-wrap">
        <div id="queueChart" style="height: 250px;"></div>
    </div>
    <div class="bigblock" >
        <h4>Данные за последние 30 мин</h4>
        <table id="queuesData" class="grid">
            <tbody>
                <tr>
                    <td class="head" style="width: 250px;">Уровень обслуживания:</td>
                    <td><span queue="la"> - 0 -</span></td>
                </tr>
                <tr>
                    <td class="head">Ожидают:</td>
                    <td><span queue="waiting"><?php echo html($this->queuesData['waiting']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">Дольше всего ожидает:</td>
                    <td><span queue="max_time"><?php echo html($this->queuesData['max_time']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">Обслуженно:</td>
                    <td><span queue="served"><?php echo html($this->queuesData['served']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">В среднем клиенты ждут:</td>
                    <td><span queue="avg_hold" ><?php echo html($this->queuesData['avg_hold']); ?></span> сек.</td>
                </tr>
                <tr>
                    <td class="head">В среднем разговор длится:</td>
                    <td><span queue="avg_call" ><?php echo html($this->queuesData['avg_call']); ?> сек.</td>
                </tr>
                <tr>
                    <td class="head">Потеряно:</td>
                    <td><span queue="lost"><?php echo html($this->queuesData['lost']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">Среднее время потеря:</td>
                    <td><span queue="avg_abandon"><?php echo html($this->queuesData['avg_abandon']); ?></span> сек.</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>


