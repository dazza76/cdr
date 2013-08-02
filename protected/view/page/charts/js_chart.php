<?php
/**
 * js_cahrt.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */
?>
<script type="text/javascript">
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {renderTo: 'container', type: 'column'},
            title: {text: chart_title },
            legend: {layout: 'vertical', align: 'right', verticalAlign: 'top', backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColorSolid) || 'white', borderColor: '#CCC', borderWidth: 1, x: 0, y: 0, floating: true, shadow: true },
            xAxis: {
                categories: <?php echo json_encode($this->highcharts[0]); ?>,
                lineWidth: 1,
            },
            yAxis: {allowDecimals: false, min: 0, lineWidth: 1, gridLineDashStyle: 'longdash', title: {text: ''}, stackLabels: { enabled: true, style: {fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'}  } },
            tooltip: {
                formatter: function() {
                    return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': ' + this.y + '<br/>';
                }
            },
            plotOptions: { column: {borderColor: '#303030', grouping: false, shadow: true }, series: { animation: { duration: 0  } } },
            series: [
                {
                    name: 'Поступило звонков',
                    data: <?php echo json_encode($this->highcharts[1]); ?>,
                    color: '#B64245',
                    dataLabels: {enabled: true, x: 0, y: 0, style: {fontSize: '13px', fontFamily: 'Verdana, sans-serif', fontWeight: 'bold', color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'} },
                },
                {
                    name: 'Принято звонков',
                    data: <?php echo json_encode($this->highcharts[2]); ?>,
                    color: "#D98962"
                },
                {
                    name: 'Переведено звонков',
                    data: <?php echo json_encode($this->highcharts[3]); ?>,
                    color: "#8bbc21"
                }
            ],
        });
        $("#highcharts-wrap tspan").last().hide();
    });
</script>