<?php
/**
 * main.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */
/* @var $this Controller */
$v = (int) App::Config()->v;
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo $this->dataPage['title']; ?></title>

        <script src="lib/jq/jquery-1.8.2.js"></script>
        <script src="lib/jq/jquery-ui-1.9.2.js"></script>
        <script src="lib/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="lib/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="lib/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="lib/jq/jquery.cookie.js"></script>

        <link href="lib/smoothness/jquery-ui.css" rel="stylesheet" >
        <link href="lib/smoothness/jquery-ui.dropdownchecklist.css" rel="stylesheet" >

        <link href="<?php  echo Utils::linkUrl('css/common.css'); ?>" rel="stylesheet" >

        <script src="<?php echo Utils::linkUrl('js/base.js'); ?>"></script>
        <script src="<?php echo Utils::linkUrl('js/filters-form.js'); ?>"></script>
        <script src="<?php echo Utils::linkUrl('js/multiselect.js'); ?>"></script>
        <script src="<?php echo Utils::linkUrl('js/fixed-header.js'); ?>"></script>
        <script src="<?php echo Utils::linkUrl('js/grit.js'); ?>"></script>
        <script src="<?php echo Utils::linkUrl('js/jplayer.js'); ?>"></script>

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