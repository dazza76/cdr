<?php
include 'filters.php';
?>
<script type="text/javascript">
    $(function() {
        var chart;
        $(document).ready(function() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
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
                    title: {
                        text: null,
                    }
                },
                tooltip: {
                    formatter: function() {
                        return '' + this.series.name + ': ' + this.y + '';
                    }
                },
                plotOptions: {
            column: {borderColor: '#303030', shadow: true },
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
    <table id="result" class="grid">
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
                <tr>
                    <td align="left"><?php echo html($queueAgent->name); ?></td>
                    <td align="left"><?php echo html($queueAgent->getStatePhone()); ?></td>
                    <td align="right">00:00:00</td>
                    <td align="right">1</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<div class="mediumblock fl_l" style="width: 400px; margin-left: 25px;">
    <div id="highcharts-wrap">
        <div id="container" style="height: 250px;"></div>
    </div>
    <div class="bigblock" >
        <h4>Данные за последние 30 мин</h4>
        <table class="grid">
            <tbody>
<!--                <tr>
                    <td class="head" style="width: 250px;">Уровень обслуживания:</td>
                    <td> - 0 - </td>
                </tr>-->
                <tr>
                    <td class="head">Ожидают:</td>
                    <td> - 0 - </td>
                </tr>
                <tr>
                    <td class="head">Дольше всего ожидает:</td>
                    <td> - 0 - </td>
                </tr>
                <tr>
                    <td class="head">В среднем клиенты ждут:</td>
                    <td> - 0 - </td>
                </tr>
                <tr>
                    <td class="head">обслуженно:</td>
                    <td> - 0 - </td>
                </tr>
                <tr>
                    <td class="head">В среднем разговор длится:</td>
                    <td> - 0 - </td>
                </tr>
                <tr>
                    <td class="head">потеряно:</td>
                    <td> - 0 - </td>
                </tr>
                <tr>
                    <td class="head">среднее время потеря:</td>
                    <td> - 0 - </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>


