<?php
include 'dialog-operators.php';
$max = 1;
foreach ($this->queueChart as $value) {
    $max += $value;
}
if ($max < 10) {
    $max = 10;
}




?>

<div class="filters clear_fix">
    <div class="filter fl_l">
        <div class="labeled">
            <span>
                <input type="hidden" id="export_type" name="export" value="1" />
                <a id="button-operators" href="#" class="icon icon-group puinter">Операторы</a>
            </span>
        </div>
    </div>
    <div class="clear clear_fix bigblock"> </div>
</div>

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

    var pageOptions = {
        section: 'operator'
    };
</script>

<div class="clear clear_fix fl_l" style="width: 600px">
    <table id="queueAgents" class="grid">
        <thead>
            <tr>
                <td class="head"  align="center" style="width: 250px">Операторы</td>
                <td class="head"  align="center" style="width: 100px" colspan="2">Статус</td>
                <td class="head"  align="center" style="width: 80px">Время</td>
                <td class="head"  align="center">Очередь</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($this->queueAgents as $queueAgent) {                ?>
                <tr agentid="<?php echo $queueAgent->agentid; ?>">
                    <td align="left" valign="top" agent="name"><?php echo html($queueAgent->name); ?></td>
                    <td align="left" valign="top" agent="state_phone"><?php echo html($queueAgent->getStatePhone()); ?></td>
                    <td align="left" valign="top" agent="state_oper"><?php echo html($queueAgent->getStateOper()); ?></td>
                    <td align="right" valign="top" agent="time"><span tick="time">00:00:00</span></td>
                    <td align="right" valign="top" agent="queues"><?php echo implode("<br />", $queueAgent->getQueuesFull(true)); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<div class="mediumblock fl_l" style="width: 400px; margin-left: 25px;">
    <div id="highcharts-wrap">
        <div id="queueChart" style="height: 250px;"></div>
    </div>

    <!-- Табличка саммари -->
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


