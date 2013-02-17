<?php
/**
 * main.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this Controller */
$v = 1;
if (ACLog::$enable) {
    $this->dataPage['links'] .= '<link href="css/aclog.css" rel="stylesheet" type="text/css" />';
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $this->dataPage['title']; ?></title>

        <link href="css/jquery-ui.css?<?php echo $v; ?>" rel="stylesheet" type="text/css">
        <link href="css/jquery-ui.dropdownchecklist.css?<?php echo $v; ?>" rel="stylesheet" type="text/css">
        <link href="css/common.css?<?php echo $v; ?>" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="js/jq/jquery-1.8.2.js"></script>
        <script type="text/javascript" src="js/jq/jquery-ui-1.9.0.custom.js"></script>
        <script type="text/javascript" src="js/jq/jquery.ui.datepicker-ru.js"></script>
        <script type="text/javascript" src="js/jq/jquery-ui-timepicker-addon-0.7.3.patched.js"></script>
        <script type="text/javascript" src="js/jq/jquery.ui.dropdownchecklist-1.4.js"></script>

        <script type="text/javascript" src="js/common.js?<?php echo $v; ?>"></script>

        <?php echo $this->dataPage['links']; ?>

        <script type="text/javascript">
<?php echo $this->dataPage['js']; ?>
        </script>
    </head>

    <body class="fixed-header">
        <div id="wrapper">
            <?php include 'header.php'; ?>

            <div id="middle">
                <?php echo $this->content; ?>

                <div style="padding-top: 50px;"><hr /></div>
            </div>
        </div>
    </body>
</html>