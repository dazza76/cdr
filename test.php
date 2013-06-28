<?php
/**
 * test.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

$t = '-';


$date = date('Y-m-d H:i:s');


echo $t - strtotime($date);