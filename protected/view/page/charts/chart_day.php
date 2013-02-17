<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this QueueController */

?>


<div class="filters clear_fix">
    <form method="get" action="" class="of_h">
        <div class="filter fl_l sep">
            <div class="label">Дата</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('Y-m-d'); ?>" class="datepicker" >
            </div>
        </div>
        <div class="filter fl_l but_search">
            <input type="submit" name="search" id="button-search" class="button" value="Показать" />
        </div>
        <input type="hidden" name="chart" value="<?php echo $this->chart; ?>" />
    </form>
</div>


<script src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {renderTo: 'container', type: 'column'},
            title: {text: 'chart'},
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                x: 0,
                y: 0,
                floating: true,
                shadow: true
            },
            xAxis: {
                categories: <?php echo json_encode($this->highcharts[0]); ?>,
                lineWidth: 1,
            },
            yAxis: {
                allowDecimals: false,
                min: 0,
                lineWidth: 1,
                gridLineDashStyle: 'longdash',
                stackLabels: {
                    enabled: true,
                    style: {fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'}
                }
            },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y + '<br/>';
                }
            },
            plotOptions: {
                column: {
                    borderColor: '#303030',
                    grouping: false,
                    shadow: true,
                }
            },
            series: [{
                    name: 'Поступило звонков',
                    data: <?php echo json_encode($this->highcharts[1]); ?>,
                    color: '#B64245',
                    dataLabels: {
                        enabled: true,
                        x: 0,
                        y: 0,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif',
                            fontWeight: 'bold',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                        }
                    },
                }, {
                    name: 'Принято звонков',
                    data: <?php echo json_encode($this->highcharts[2]); ?>,
                    color: "#D98962"
                }],
        });

        $("#highcharts-wrap tspan").last().hide();
    });
</script>

<div id="highcharts-wrap" class="clear clear_fix bigblock" style="width: 100%">
    <div id="container" style="width: 1240px; height: 400px;"></div>
</div>

