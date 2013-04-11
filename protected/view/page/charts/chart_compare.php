<?php
/**
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this QueueController */
switch ($this->compareType) {
    case 'week':
        $from_title  = "<b>" . $this->fromdate->format('W') . "</b> неделю <b>" . $this->fromdate->format('Y') . "</b>г.";
        $to_title    = "<b>" . $this->todate->format('W') . "</b> неделю <b>" . $this->todate->format('Y') . "</b>г.";
        break;
    case 'month':
        $from_title  = "<b>" . $this->fromdate->format('F Y') . "</b>г.";
        $to_title    = "<b>" . $this->todate->format('F Y') . "</b>г.";
        break;
    case 'day' :
    default :
        $from_title  = "<b>" . $this->fromdate->format('d.m.Y') . "</b>";
        $to_title    = "<b>" . $this->todate->format('d.m.Y') . "</b>";
        break;
}
$chart_title = "сравнение {$from_title} и {$to_title}";
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
                <input name="fromdate" type="text" autocomplete="off" value="<?php echo $this->fromdate->format('d.m.Y'); ?>" class="datepicker" showweek="1" >
                —
                <input name="todate" type="text" autocomplete="off" value="<?php echo $this->todate->format('d.m.Y'); ?>" class="datepicker" showweek="1" >
            </div>
        </div>
        <div class="filter fl_l sep">
            <div class="label">Очередь</div>
            <div class="labeled">
                    <?php echo Queue::showMultiple("queue[]", $this->queue); ?>
            </div>
        </div>
        
        <div class="filter fl_l sep">
            <div class="label">мобильные</div>
            <div class="labeled" style="padding: 3px 0px 4px 0px;">
                <input name="mob" type="checkbox" value="1" <?php if ($this->mob) echo "default=\"1\""; ?> />
            </div>
        </div>   
        
        
        
        <div class="filter fl_l">
            <div class="labeled">
                <input type="submit" name="search" id="button-search" class="button button-search" value="Показать" />
            </div>
        </div>
        <input type="hidden" name="section" value="<?php echo $this->getSection(); ?>" />
    </form>
</div>

<div id="highcharts-wrap" class="clear clear_fix bigblock" style="width: 100%">
    <div id="container" style="width: 1240px; height: 400px;"></div>
    <br />
    <div id="container2" style="width: 1240px; height: 400px;"></div>
</div>


<script type="text/javascript">
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {renderTo: 'container', type: 'spline'},
            title: {text: '<?php echo $chart_title; ?>'},
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
                title: {text: 'количество'},
                stackLabels: {
                    enabled: true,
                    style: {fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'}
                }
            },
            tooltip: {
                // crosshairs: true,
                // shared: true
                formatter: function() {
                    return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y + '<br/>';
                }
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    },
                    animation: {duration: 0}
                }
            },
            series: [{
                    name: 'Поступило звонков  за <?php echo $from_title; ?>',
                    data: <?php echo json_encode($this->highcharts['total'][1]); ?>,
                    color: '#B64245',
                }, {
                    name: 'Поступило звонков  за <?php echo $to_title; ?>',
                    data: <?php echo json_encode($this->highcharts['total'][2]); ?>,
                    color: "#D98962",
                }],
        });



        var chart2;
        chart2 = new Highcharts.Chart({
            chart: {renderTo: 'container2', type: 'spline'},
            title: {text: '<?php echo $chart_title; ?>'},
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
                title: {text: 'количество'},
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
                    },
                    animation: {duration: 0}
                }
            },
            series: [{
                    name: 'Принято звонков за <?php echo $from_title; ?>',
                    data: <?php echo json_encode($this->highcharts['complete'][1]); ?>,
                    color: '#B64245',
                }, {
                    name: 'Принято звонков за <?php echo $to_title; ?>',
                    data: <?php echo json_encode($this->highcharts['complete'][2]); ?>,
                    color: "#D98962",
                }],
        });
        $('tspan:contains("Highcharts.com")').hide();
    });
</script>

