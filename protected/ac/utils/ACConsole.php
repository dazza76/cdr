<?php
/**
 * ACConsole class  - ACConsole.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  (c) 2013, AC
 */

/**
 * ACConsole class
 *
 * @package		AC
 */
class ACConsole {

    public $pwd;
    public $argv;

    function __construct($argv) {
        $this->pwd  = $this->parsePath($_SERVER['PWD']);
        $this->argv = $this->parseArgs($argv);
    }

    /**
     * PARSE ARGUMENTS
     *
     * [pfisher ~]$ echo "<?php
     * >     include('CommandLine.php');
     * >     \$args = CommandLine::parseArgs(\$_SERVER['argv']);
     * >     echo "\n", '\$out = '; var_dump(\$args); echo "\n";
     * > ?>" > test.php
     *
     * [pfisher ~]$ php test.php plain-arg --foo --bar=baz --funny="spam=eggs" --alsofunny=spam=eggs \
     * > 'plain arg 2' -abc -k=value "plain arg 3" --s="original" --s='overwrite' --s
     *
     * $out = array(12) {
     *   [0]                => string(9) "plain-arg"
     *   ["foo"]            => bool(true)
     *   ["bar"]            => string(3) "baz"
     *   ["funny"]          => string(9) "spam=eggs"
     *   ["alsofunny"]      => string(9) "spam=eggs"
     *   [1]                => string(11) "plain arg 2"
     *   ["a"]              => bool(true)
     *   ["b"]              => bool(true)
     *   ["c"]              => bool(true)
     *   ["k"]              => string(5) "value"
     *   [2]                => string(11) "plain arg 3"
     *   ["s"]              => string(9) "overwrite"
     * }
     *
     * @author              Patrick Fisher <patrick@pwfisher.com>
     * @since               August 21, 2009
     * @see                 http://www.php.net/manual/en/features.commandline.php
     *                      #81042 function arguments($argv) by technorati at gmail dot com, 12-Feb-2008
     *                      #78651 function getArgs($args) by B Crawford, 22-Oct-2007
     * @usage               $args = CommandLine::parseArgs($_SERVER['argv']);
     */
    public function parseArgs($argv) {

        array_shift($argv);
        $out = array();

        foreach ($argv as $arg) {

            // --foo --bar=baz
            if (substr($arg, 0, 2) == '--') {
                $eqPos = strpos($arg, '=');

                // --foo
                if ($eqPos === false) {
                    $key       = substr($arg, 2);
                    $value     = isset($out[$key]) ? $out[$key] : true;
                    $out[$key] = $value;
                }
                // --bar=baz
                else {
                    $key       = substr($arg, 2, $eqPos - 2);
                    $value     = substr($arg, $eqPos + 1);
                    $out[$key] = $value;
                }
            }
            // -k=value -abc
            else if (substr($arg, 0, 1) == '-') {

                // -k=value
                if (substr($arg, 2, 1) == '=') {
                    $key       = substr($arg, 1, 1);
                    $value     = substr($arg, 3);
                    $out[$key] = $value;
                }
                // -abc
                else {
                    $chars = str_split(substr($arg, 1));
                    foreach ($chars as $char) {
                        $key       = $char;
                        $value     = isset($out[$key]) ? $out[$key] : true;
                        $out[$key] = $value;
                    }
                }
            }
            // plain-arg
            else {
                $value = $arg;
                $out[] = $value;
            }
        }
        return $out;
    }

    /**
     * Преобразует путь, из типа "cygwin" в тип windows
     * @param string $path
     * @return string
     */
    public function parsePath($path) {
        $path = preg_replace('#^/cygdrive/([a-z])#', '$1:', $path);
        $path = preg_replace('#^/#', 'c:/cygwin/', $path);
        $path = preg_replace(array('|/+|', '|^/|'), array('/', ''), $path . '/');

        return $path;
    }
}