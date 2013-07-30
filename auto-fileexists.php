<?php
/**
 * test.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

require_once 'protected/bootstrap.php';

$_SERVER['DOCUMENT_ROOT'] = substr(__DIR__, 0, -strlen(App::Config()->webpath));
//App::Config()->webpath

$app             = new Application();
$app->controller = new CdrController();
$controller = $app->controller;

foreach ($argv as $val) {
    if (strpos($val, "--fromdate") === 0) {
        $fromdate = substr($val, 11);
    }
    if (strpos($val, "--todate") === 0) {
        $todate = substr($val, 9);
    }
    if (strpos($val, "--log") === 0) {
        $controller->test_cli = true;
    }
}



if(!ACValidation::date($todate)) {
    $todate = date("Y-m-d");
}
if(!ACValidation::date($fromdate)) {
    $fromdate = date("Y-m-d", mktime(0, 0, 0, date("n"), date("d")-1, date("Y")));
}


$controller->fromdate = $fromdate;
$controller->todate = $todate;

do {
   $rows =  $controller->actionCheckFile();
}
while ($rows);


//$controller->actionCheckFile(500);
//
//exit;


// UPDATE `cdr` SET `file_exists`=NULL WHERE 1

/* @var $cdr Cdr */
//$cdr = App::Db()->createCommand()->select('id, calldate, uniqueid, dcontext')
//        ->addWhere('uniqueid', '1353063786.4733')
//        ->from(Cdr)
//        ->limit(1)
//        ->query()
//        ->fetchObject(Cdr);


//echo "getFileExistsInPath: ".$cdr->getFileExistsInPath()."\n";
// echo $cdr->file_exists ;
// echo $cdr->getFile()."\n";
//echo $cdr->getTime();

// print_r($cdr);
// echo "\n";
//echo "File: ".$cdr->getFile()."\n";

