<?php
/* AC: v: */

/**
 * select.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
require_once 'protected/bootstrap.php';

$queue = array(
    " " =>"Все очереди",
    "3001"=>"Скорая помощь",
    "3002"=>"ДМС",
    "3003"=>"Госпитализация",
    "3004"=>"Водоканал",
    "3005"=>"Педиатрия",
    "3006"=>"ЛПУ",
    "3009"=>"МЦ",
);
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>

        <link href="js/lib/ui.dropdownchecklist.standalone.css?1" rel="stylesheet" >

        <script src="js/jq/jquery-1.8.2.js"></script>
        <script src="js/jq/jquery-ui-1.9.2.js"></script>

        <script src="js/lib/ui.dropdownchecklist-1.4.js"></script>
        <script src="js/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="js/filters-form.js"></script>
        <script src="js/multiselect.js"></script>

    </head>
    <body>
        <form method="get">

            <div style="margin: 50px;" >
                <select name="oper[]" size="1" multiple="multiple" >
                    <option value="" >Все очереди</option>
                    <option value="3001">Скорая помощь</option>
                    <option value="3002">ДМС</option>
                    <option value="3003">Госпитализация</option>
                    <option value="3004">Водоканал</option>
                    <option value="3005" selected="selected">Педиатрия</option>
                    <option value="3006">ЛПУ</option>
                    <option value="3009" selected="selected">МЦ</option>
                </select>
            </div>
            <div>
                <?php echo ACHtml::select($queue, "oper[]" , array("size"=>"1", "multiple"=>"multiple"), array("3001", "3009")); ?>

            </div>

            <div><input type="submit" /> </div>
        </form>
        <div><?php var_dump($_GET); ?></div>
    </body>
</html>