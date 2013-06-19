<?php
//  $config = array(
//      'config'=>'localhost'
//  );
require_once 'protected/bootstrap.php';

$app             = new Application();
$app->controller = new SupervisorController();
$app->controller->init();
$app->controller->render();

Log::render();
// ----------------------------------------------------------------------------