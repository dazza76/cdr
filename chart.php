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
$graph->draw();
$graph->destroy();

exit();