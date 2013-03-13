<?php
//$config = array(
//    "config" => 'localhost'
//);
require_once 'protected/bootstrap.php';
// ----------------------------------------------------------------------------
App::Config()->v = 5;

$app   = new Application();


$class = $app->request->page . 'Controller';
if ( ! class_exists($class)) {
    die('Страница не найдена');
}
App::Config()->mod_rewrite = true;
App::Config()->page_prefix = '';
Log::trace('Controller(mod_rewrite): ' . $app->request->page);

$app->controller = new $class();
$app->controller->init();
//
$app->response->send();
$app->controller->render();


if ($app->controller->getActType() == Controller::TYPE_PAGE) {
    Log::render();
}


