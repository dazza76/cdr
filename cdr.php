<?php
$config = array(
    "database" => array(
    )
);
require_once 'protected/bootstrap.php';
// ----------------------------------------------------------------------------

$app = new Application($config);

$app->controller = new CdrController();
$app->controller->init();
$app->controller->render();

ACLog::render();