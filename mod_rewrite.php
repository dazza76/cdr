<?php
$config = array(
    "database" => array(
    )
);
require_once 'protected/bootstrap.php';
// ----------------------------------------------------------------------------

//die("mod_rewrite - ok");



$app = new Application();

var_dump($app);

// $class = $app->request->page.'Controller';
// if(!class_exists($class)) {
//     die('Страница не найдена');
// }
//
// $app->controller = new $class();
// $app->controller->init();
//
// $app->response->send();
// $app->controller->render();
