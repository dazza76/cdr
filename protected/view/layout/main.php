<?php
/**
 * main.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this Controller */
$v = 2;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $this->dataPage['title']; ?></title>

        <link href="css/jquery-ui.css?<?php echo $v; ?>" rel="stylesheet" >
        <link href="css/jquery-ui.dropdownchecklist.css?<?php echo $v; ?>" rel="stylesheet" >

        <script src="js/jq/jquery-1.8.2.js"></script>
        <script src="js/jq/jquery-ui-1.9.2.js"></script>
        <script src="js/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="js/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="js/jq/jquery.ui.dropdownchecklist.js"></script>

        <link href="css/common.css?<?php echo $v; ?>" rel="stylesheet" >

        <script src="js/base.js?<?php echo $v; ?>"></script>
        <script src="js/filters-form.js?<?php echo $v; ?>"></script>
        <script src="js/multiselect.js?<?php echo $v; ?>"></script>
        <script src="js/fixed-header.js?<?php echo $v; ?>"></script>
        <script src="js/grit.js?<?php echo $v; ?>"></script>
        <script src="js/jplayer.js?<?php echo $v; ?>"></script>

        <?php echo $this->dataPage['links']; ?>

        <script type="text/javascript">
<?php echo $this->dataPage['js']; ?>
        </script>
    </head>

    <body class="fixed-header">
        <div id="wrapper" >
            <?php include 'header.php'; ?>

            <div id="middle" class="">
                <?php echo $this->content; ?>

                <div class="clear" style="padding-top: 50px;"><hr /></div>
            </div>
        </div>
    </body>
</html>