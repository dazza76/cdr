<?php
$config = array(
    "database" => array(
    )
);
define('DEBUG', 0);
require_once 'protected/bootstrap.php';



$app = new Application($config);
$app->controller = new QueueController();
$app->controller->init();
$app->controller->render();

ACLog::render();

