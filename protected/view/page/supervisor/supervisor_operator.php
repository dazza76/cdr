<?php
include 'filters.php';
$max = 1;
foreach ($this->queueChart as $value) {
    $max += $value;
}
if ($max < 10) {
    $max = 10;
}
?>
<script type="text/javascript">
    var chart;
    var chartOptions = {
        chart: {renderTo: 'queueChart', type: 'column'},
        title: {align: 'left', text: 'Всего операторов'},
        legend: {layout: 'vertical', align: 'right', verticalAlign: 'top', y: 40},
        xAxis: {categories: [' ']},
        yAxis: {
            allowDecimals: false,
            max: <?php echo $max; ?>,
            title: {text: null}
        },
        tooltip: {formatter: function() {
                return '' + this.series.name + ': ' + this.y + '';
            }},
        plotOptions: {column: {borderColor: '#303030', shadow: true}, series: {animation: {duration: 0}}},
        credits: {enabled: false},
        series: [{
                name: 'свободных',
                data: [0]
            }, {
                name: 'разговаривает',
                data: [0]
            }, {
                name: 'обработка',
                data: [0]
            }, {
                name: 'перерыв',
                data: [0]
            }, {
                name: 'звонит',
                data: [0]
            }
        ]
    };

    $(document).ready(function() {
        var series = [
            <?php
            echo $this->queueChart['free']
             . ',' . $this->queueChart['used']
             . ',' . $this->queueChart['aftercall']
             . ',' . $this->queueChart['paused']
             . ',' . $this->queueChart['ringing'];
            ?>
        ];
        for(var i in series) {
            chartOptions.series[i].data = [series[i]];
        }
        chart = new Highcharts.Chart(chartOptions);
    });
</script>


<div class="filters clear_fix mediumblock of_h">
    <table class="grid">
        <thead height="50px">
            <tr>
                <th>Операторы</th>
                <th>Статус</th>
                <th>Время</th>
                <th>Очередь</th>
            </tr>
        </thead>
    </table>
</div>

<div class="clear clear_fix fl_l" style="width: 600px">
    <table id="queueAgents" class="grid">
        <thead>
            <tr class="b-head">
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
                    <td queue="service"><span><?php echo html($this->queuesData['service']); ?></span> %</td>
                </tr>
                <tr>
                    <td class="head">Ожидают:</td>
                    <td queue="waiting"><span><?php echo html($this->queuesData['waiting']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">Дольше всего ожидает:</td>
                    <td queue="max_time"><span><?php echo html($this->queuesData['max_time']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">Обслуженно:</td>
                    <td queue="served"><span><?php echo html($this->queuesData['served']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">В среднем клиенты ждут:</td>
                    <td queue="avg_hold"><span><?php echo html($this->queuesData['avg_hold']); ?></span> сек.</td>
                </tr>
                <tr>
                    <td class="head">В среднем разговор длится:</td>
                    <td queue="avg_call"><span><?php echo html($this->queuesData['avg_call']); ?></span> сек.</td>
                </tr>
                <tr>
                    <td class="head">Потеряно:</td>
                    <td queue="lost"><span><?php echo html($this->queuesData['lost']); ?></span></td>
                </tr>
                <tr>
                    <td class="head">Среднее время потеря:</td>
                    <td queue="avg_abandon"><span><?php echo html($this->queuesData['avg_abandon']); ?></span> сек.</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>


