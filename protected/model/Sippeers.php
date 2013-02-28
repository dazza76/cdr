<?php
/**
 * Sippeers class
 *
 * @package		AC
 */
class Sippeers {

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

}