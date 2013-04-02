<?php

/**
 * Sippeers class
 *
 * @property int 	  $id                -
 * @property string   $accountcode       -
 * @property string   $disallow          -
 * @property string   $allow             -
 * @property string   $allowoverlap      -
 * @property string   $allowsubscribe    -
 * @property string   $allowtransfer     -
 * @property string   $amaflags          -
 * @property string   $autoframing       -
 * @property string   $auth              -
 * @property int 	  $busylevel         -
 * @property string   $buggymwi          -
 * @property string   $callgroup         -
 * @property string   $callerid          -
 * @property string   $cid_number        -
 * @property string   $fullname          -
 * @property int 	  $call_limit        -
 * @property string   $callingpres       -
 * @property string   $canreinvite       -
 * @property string   $context           -
 * @property string   $defaultip         -
 * @property string   $dtmfmode          -
 * @property string   $fromuser          -
 * @property string   $fromdomain        -
 * @property string   $fullcontact       -
 * @property string   $g726nonstandard   -
 * @property string   $host              -
 * @property string   $insecure          -
 * @property string   $ipaddr            -
 * @property string   $language          -
 * @property string   $lastms            -
 * @property string   $limitonpeer       -
 * @property string   $mailbox           -
 * @property int 	  $maxcallbitrate    -
 * @property string   $mohsuggest        -
 * @property string   $md5secret         -
 * @property string   $musiconhold       -
 * @property string   $name              -
 * @property string   $nat               -
 * @property string   $notifyhold        -
 * @property string   $notifyringing     -
 * @property string   $outboundproxy     -
 * @property string   $deny              -
 * @property string   $permit            -
 * @property string   $pickupgroup       -
 * @property string   $port              -
 * @property string   $progressinband    -
 * @property string   $promiscredir      -
 * @property string   $qualify           -
 * @property string   $regexten          -
 * @property int 	  $regseconds        -
 * @property string   $regserver         -
 * @property string   $rfc2833compensate -
 * @property string   $rtptimeout        -
 * @property string   $rtpholdtimeout    -
 * @property string   $secret            -
 * @property string   $sendrpid          -
 * @property string   $setvar            -
 * @property string   $subscribecontext  -
 * @property string   $subscribemwi      -
 * @property string   $t38pt_udptl       -
 * @property string   $trustrpid         -
 * @property string   $type              -
 * @property string   $useclientcode     -
 * @property string   $user              -
 * @property string   $useragent         -
 * @property string   $username          -
 * @property string   $usereqphone       -
 * @property string   $videosupport      -
 * @property string   $vmexten           -
 */
class Sippeers extends ACDataObject {

    const TABLE = "sippeers";

    public static $fields = array(
    );
    public $expert = array('accountcode', 'allowoverlap', 'amaflags', 'autoframing',
        'buggymwi', 'callingpres', 'canreinvite', 'defaultip', 'fromuser', 'fromdomain',
        'fullcontact', 'g726nonstandard', 'insecure', 'lastms', 'mailbox', 'maxcallbitrate',
        'mohsuggest', 'musiconhold', 'outboundproxy', 'deny', 'permit', 'port', 'progressinband',
        'promiscredir', 'qualify', 'regexten', 'regseconds', 'regserver', 'rfc2833compensate',
        'rtptimeout', 'rtpholdtimeout', 'sendrpid', 'setvar', 'subscribecontext',
        'subscribemwi', 't38pt_udptl', 'trustrpid', 'useclientcode', 'usereqphone',
        'vmexten');
    public $main   = array('disallow', 'allow', 'allowsubscribe', 'allowtransfer',
        'auth', 'busylevel', 'callgroup', 'callerid', 'cid_number', 'fullname', 'call-limit',
        'context', 'dtmfmode', 'host', 'ipaddr', 'language', 'name', 'nat', 'pickupgroup',
        'type', 'user', 'username', 'videosupport');
    public $hidden = array(
        'md5secret', 'useragent'
    );
    
    
    public function __set($name, $value) {
        if($name == 'call-limit') {
            $name = 'call_limit';
        }
        parent::__set($name, $value);
    }
}