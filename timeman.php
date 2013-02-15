<?php
define('DEBUG', 0);
require_once 'protected/bootstrap.php';

$config = array(
    "database" => array(
    	// "dbname" => "cmri"
    )
);

$app = new Application($config);
$app->controller = new TimemanController();
$app->controller->init();
$app->controller->render();

ACLog::render();