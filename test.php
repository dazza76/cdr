<?php
require_once 'protected/bootstrap.php';

$app = new Application();
//$app->controller = new CdrController();
//$app->controller->init();
//$app->controller->render();


//$date = '2012-11-16 14:40:33';


echo $_SERVER['DOCUMENT_ROOT'] .Cdr::monitorFile('1353063776.4732', '2012-11-16 14:40:33');

?>