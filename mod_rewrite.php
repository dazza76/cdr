<?php
/* cdr.php
 * ------------------------------------------------------------------
  $config = array(
  //    'config'=>'localhost'
  );
  require_once 'protected/bootstrap.php';

  $app = new Application();
  $app->controller = new CdrController();
  $app->controller->init();
  $app->controller->render();

  Log::render();
  // ----------------------------------------------------------------------------
 */

$config = array(
    "config" => 'localhost'
);
require_once 'protected/bootstrap.php';
// ----------------------------------------------------------------------------
App::Config()->v = 5;

$app   = new Application();


$class = $app->request->page . 'Controller';
if ( ! class_exists($class)) {
    die('Страница не найдена');
}
Log::trace('Controller: ' . $app->request->page);

$app->controller = new $class();
$app->controller->init();
//
$app->response->send();
$app->controller->render();

if ($app->controller->getActType() == Controller::TYPE_PAGE) {
    Log::render();
}
