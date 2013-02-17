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
            <div class="label">Тип</div>
            <div class="labeled">
                <select name="compareType" size="1"  default="<?php echo $this->compareType; ?>">
                    <option value="day">суточный</option>
                    <option value="week">недельный</option>
                    <option value="month">месячный</option>
                </select>
            </div>
        </div>



        <div class="filter fl_l sep">
            <div class="label">Дата сравнения</div>
            <div class="labeled">
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('Y-m-d'); ?>" class="datepicker" showweek="1" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('Y-m-d'); ?>" class="datepicker" showweek="1" >
            </div>
        </div>
        <div class="filter fl_l but_search">
            <input type="submit" name="search" id="button-search" class="button" class="button" value="Показать" />
        </div>
        <input type="hidden" name="chart" value="<?php echo $this->chart; ?>" />
    </form>
</div>

<div id="highcharts-wrap" class="clear clear_fix bigblock" style="width: 100%">
    <div id="container" style="width: 1240px; height: 400px;"></div>
    <div id="container2" style="width: 1240px; height: 400px;"></div>
</div>

<script src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {renderTo: 'container', type: 'spline'},
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
                categories: <?php echo json_encode($this->highcharts['total'][0]); ?>,
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
                crosshairs: true,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                    name: 'Поступило звонков  за <b><?php echo $this->fromdate->format('Y-m-d'); ?></b>',
                    data: <?php echo json_encode($this->highcharts['total'][1]); ?>,
                    color: '#B64245',
                }, {
                    name: 'Поступило звонков  за <b><?php echo $this->todate->format('Y-m-d'); ?></b>',
                    data: <?php echo json_encode($this->highcharts['total'][2]); ?>,
                    color: "#D98962",
                }],
        });



        var chart2;
        chart2 = new Highcharts.Chart({
            chart: {renderTo: 'container2', type: 'spline'},
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
                categories: <?php echo json_encode($this->highcharts['complete'][0]); ?>,
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
                crosshairs: true,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                    name: 'Принято звонков за <b><?php echo $this->fromdate->format('Y-m-d'); ?></b>',
                    data: <?php echo json_encode($this->highcharts['complete'][1]); ?>,
                    color: '#B64245',
                }, {
                    name: 'Принято звонков за <b><?php echo $this->todate->format('Y-m-d'); ?></b>',
                    data: <?php echo json_encode($this->highcharts['complete'][2]); ?>,
                    color: "#D98962",
                }],
        });
        // data-highcharts-chart  Highcharts.com
        //$("#highcharts-wrap  tspan").last().hide();
        $('tspan:contains("Highcharts.com")').hide();
    });
</script>

