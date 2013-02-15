<?php
/**
 * chart.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
$config = array(
    "debug" => 0
);

require_once 'protected/bootstrap.php';
error_reporting(0);


$app = new Application();

$controller = new QueueController();
$app->controller = $controller;

$controller->fromdate = $_GET['fromdate'];

$rotation = false;
switch ($_GET['chart']) {
    case 'day':
        $data = $controller->getDataStatisticDay();
        break;
    case 'week':
        $data = $controller->getDataStatisticWeek();
        break;
    case 'month':
        $data = $controller->getDataStatisticMonth();
        break;
    default:
        $data = array();
        break;
}

$graph = new GraphQueue($data);
$graph->rotation = $rotation;
$graph->init();
$graph->draw(); exit();

//ac_dump($graph);

//



// 00:00,2,2;01:00,26,26;02:00,161,150;03:00,100,90;04:00,250,200;05:00,48,40;06:00,206,200;07:00,360,355;08:00,26,26;09:00,161,150;10:00,100,90;11:00,250,200;12:00,48,40;13:00,2,2;14:00,26,26;15:00,161,150;16:00,100,90;17:00,250,200;18:00,48,40;19:00,206,200;20:00,2,2;21:00,26,26;22:00,161,150;23:00,100,90;
