<?php
/**
 * info.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

require_once 'protected/bootstrap.php';
$app             = new Application();

$tables = array();

$result = App::Db()->query("SHOW TABLES");
while($row = $result->fetch_array(MYSQL_NUM)) {
    $tb_name =$row[0];

    $tb_describe = array();
    $res = App::Db()->query("DESCRIBE $tb_name");
    while($cl = $res->fetch_row()) {
        $tb_describe[] = $cl;
    }

    $tables[$tb_name] = $tb_describe;
}

print_r($tables);


//var_dump($tables);