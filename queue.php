<?php
require_once 'protected/bootstrap.php';

$app             = new Application();
$app->controller = new QueueController();
$app->controller->init();
$app->controller->render();

Log::render();
// ----------------------------------------------------------------------------