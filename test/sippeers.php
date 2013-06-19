<?php
require_once 'protected/bootstrap.php';

$app = new Application();

$expert = array('accountcode', 'allowoverlap', 'amaflags', 'autoframing', 'buggymwi',
    'callingpres', 'canreinvite', 'defaultip', 'fromuser', 'fromdomain', 'fullcontact',
    'g726nonstandard', 'insecure', 'lastms', 'mailbox', 'maxcallbitrate', 'mohsuggest',
    'musiconhold', 'outboundproxy', 'deny', 'permit', 'port', 'progressinband', 'promiscredir',
    'qualify', 'regexten', 'regseconds', 'regserver', 'rfc2833compensate', 'rtptimeout',
    'rtpholdtimeout', 'sendrpid', 'setvar', 'subscribecontext', 'subscribemwi', 't38pt_udptl',
    'trustrpid', 'useclientcode', 'usereqphone', 'vmexten');

$main = array('disallow', 'allow', 'allowsubscribe', 'allowtransfer', 'auth', 'busylevel',
    'callgroup', 'callerid', 'cid_number', 'fullname', 'call-limit', 'context', 'dtmfmode',
    'host', 'ipaddr', 'language', 'name', 'nat', 'pickupgroup', 'type', 'user', 'username',
    'videosupport');

//if ( ! ((isset($_POST["submit"])) && ($_POST["submit"] == "OK"))) {
//    $html_out = "<form method=\"POST\"><table border=1><tr><td colspan=2>Основные</td></tr>";
//    foreach ($main as $value)
//        $html_out .= ("<tr><td>$value</td><td><input type=text name=\"$value\"/></td></tr>");
//    $html_out .= "<tr><td colspan=2>Расширенные</td></tr>";
//    foreach ($expert as $value)
//        $html_out .= ("<tr><td>$value</td><td><input type=text name=\"$value\"/></td></tr>");
//    $html_out .= "<tr><td colspan=2><input type=submit name=submit value=\"OK\"/></td></tr></table></form>";
//
//    echo $html_out;
//} else {
//    foreach ($_POST as $key => $value) {
//        if (( ! is_numeric($key)) && ($key != "submit") && ($value)) {
//            $valuenames .= ",`$key`";
//            $values .= ",'$value'";
//        };
//    };
//    $valuenames = "(" . substr($valuenames, 1) . ")";
//    $values     = "(" . substr($values, 1) . ")";
//    echo $query      = "INSERT INTO sippeers $valuenames VALUES $values;";
//
////	if(mysql_query($query,$dbconn))
//    echo BR."Successful";
////	else
////		echo mysql_error();
//}


require_once './protected/view/page/page-sippeers.php';

