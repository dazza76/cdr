<?php
require_once 'protected/bootstrap.php';

$app             = new Application();
$app->controller = new TimemanController();
$app->controller->init();
$app->controller->render();

Log::render();
// ----------------------------------------------------------------------------