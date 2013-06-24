<?php
/**
 * test.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
$rar = array(
    'application/x-gtar',
    'application/x-gzip',
    'application/x-rar',
    'application/zip',
);
$dir = dirname(__FILE__);


if ( ! empty($_POST['submit'])) {
    header('Content-Type: text/plain; charset=windows-1251');

    foreach ($_FILES as $key => $value) {
        if(!$value['tmp_name'] ) {
            continue;
        }
        
        $save = "{$dir}/docs/{$key}";

        $shell         = "file -i -b " .  $value['tmp_name'] . " | cut -d';' -f1";
        $value['type'] = trim(shell_exec($shell));

        if (in_array($value['type'], $rar)) {
            $save .= '.zip';
            copy($value['tmp_name'], $save);
        } else {
            $save .= '.tar.gz';
            
            $value['shell'] = "tar -zacf {$save} {$value['tmp_name']}";
            shell_exec($value['shell']);
        }
        $value['save'] = $save;

        print_r($value);
    }

    exit();
}
?>
<form method="post" enctype="multipart/form-data">
    <input name="file_1" type="file" /><br />
    <input name="file_2" type="file" /><br />
    <input name="file_3" type="file" /><br />
    <input name="file_4" type="file" /><br />
    <br />
    <input type="submit" name="submit" value="submit" />
</form>