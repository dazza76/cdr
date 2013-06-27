<?php
require_once 'protected/bootstrap.php';

$app = new Application();

// App::location('cdr');

        if (isset($_POST['slogin'])) {
            App::Auth()->login();
            LOG::dump(App::Auth(), 'lllllllll');
            // App::refresh();

        }






        if (isset($_REQUEST['logout'])) {
            App::Auth()->logout();
            App::location('login');
        }


        if (App::Auth()->isAuth()) {
            App::location('index');
            exit();
        }

        log::dump(ACSession::get('user'), 'session')

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <script src="lib/jq/jquery-1.8.2.js"></script>
        <script src="lib/jq/jquery-ui-1.9.2.js"></script>
        <script src="lib/jq/jquery.ui.datepicker-ru.js"></script>
        <script src="lib/jq/jquery.ui.timepicker.addon.js"></script>
        <script src="lib/jq/jquery.ui.dropdownchecklist.js"></script>
        <script src="lib/jq/jquery.cookie.js"></script>

        <link rel="stylesheet" href="css/style.css" type="text/css" />
        <link rel="stylesheet" href="css/ui/ui.css" type="text/css" />
        <link rel="stylesheet" href="css/ui/ui-button.css" type="text/css" />
        <link rel="stylesheet" href="css/ui/ui-icon.css" type="text/css" />



        <title>Авторизация</title>


        <script type="text/javascript">
            $(document).ready(function() {
                var $loginBox = $("#login_box");
                $loginBox.dialog({
                    resizable: false,
                    autoOpen: true,
                    minHeight: 180,
                    width: 500,
                    modal: true,
                    draggable: false,
                    dialogClass: "login",
                    buttons: {
                        "Войти": function() {
                            $loginBox.find("form").submit();
                            // $(this).dialog("close");
                        }
                    }
                });
                $loginBox.parent(".login").find("a[role='button']").hide();
                $loginBox.find("input").attr("maxlength", "20")
                        .attr("autocomplete", "off")
                        .keydown(function(evt) {
                    var charCode = (evt.which) ? evt.which : evt.keyCode;
                    if (charCode === 13) {
                        $loginBox.find("form").submit();
                        return false;
                    }

                    return true;
                });

            });

        </script>

    </head>
    <body>
        <div id="login_box" class="dialog hidden" title="Авторизация" >
            <form id="loginform" method="post" >

                <input name="login" type="hidden" value="1" />

<!--                 <div class="ui-widget hidden">
                    <div class="ui-state-error ui-corner-all" style="margin-top: 10px; padding: 0 .7em;">
                        <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
                            <strong>Ошибка:</strong> Не верно введен логин или пароль.</p>
                    </div>
                </div> -->

                <div style="padding: 10px 0px">
                    <div class="clear clear_fix bigblock">
                        <div class="label fl_l ta_r">Логин:</div>
                        <div class="labeled fl_l">
                            <input name="slogin" type="text" class="ui-widget-content ui-corner-all" />
                        </div>
                    </div>
                    <div class="clear clear_fix bigblock">
                        <div class="label fl_l ta_r">Пароль:</div>
                        <div class="labeled fl_l">
                            <input name="spass" type="password" class="ui-widget-content ui-corner-all" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
<?php
// log::render();
?>