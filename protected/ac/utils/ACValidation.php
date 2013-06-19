<?php

/**
 * Offers different validation methods.
 *
 * @package       Cake.Utility
 * @since         CakePHP v 1.2.0.3830
 */
class ACValidation {

    /**
     * Some complex patterns needed in multiple places
     *
     * @var array
     */
    protected static $_pattern = array(
        'hostname' => '(?:[-_a-z0-9][-_a-z0-9]*\.)*(?:[a-z0-9][-a-z0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,4}|museum|travel)'
    );

    /**
     * Holds an array of errors messages set in this class.
     * These are used for debugging purposes
     *
     * @var array
     */
    public static $errors = array();

    /**
     * Проверка, что строка содержит что-то кроме пробела
     *
     * Возвращает истину, если строка содержит что-то кроме пробела
     *
     * $check can be passed as an array:
     * array('check' => 'valueToCheck');
     *
     * @param string|array $check Value to check
     * @return boolean Success
     */
    public static function notEmpty($check){
        if (is_array($check)) {
            extract(self::_defaults($check));
        }

        if (empty($check) && $check != '0') {
            return false;
        }
        return self::_check($check, '/[^\s]+/m');
    }

    /**
     * Проверка, что строка содержит только целые или букв
     *
     * $check can be passed as an array:
     * array('check' => 'valueToCheck');
     *
     * @param string|array $check Value to check
     * @return boolean Success
     */
    public static function alphaNumeric($check){
        if (is_array($check)) {
            extract(self::_defaults($check));
        }

        if (empty($check) && $check != '0') {
            return false;
        }
        return self::_check($check, '/^[\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]+$/mu');
    }

    /**
     * Проверяет, что длина строки находится в пределах указанного диапазона с.
     * Пространства включены в букв.
     * Возврат правда, то строка соответствует значению мин, макс, или между мин и макс,
     *
     * @param string $check Value to check for length
     * @param integer $min Minimum value in range (inclusive)
     * @param integer $max Maximum value in range (inclusive)
     * @return boolean Success
     */
    public static function between($check, $min, $max){
        $length = mb_strlen($check);
        return ($length >= $min && $length <= $max);
    }

    /**
     * Returns true if field is left blank -OR- only whitespace characters are present in it's value
     * Whitespace characters include Space, Tab, Carriage Return, Newline
     *
     * $check can be passed as an array:
     * array('check' => 'valueToCheck');
     *
     * @param string|array $check Value to check
     * @return boolean Success
     */
    public static function blank($check){
        if (is_array($check)) {
            extract(self::_defaults($check));
        }
        return !self::_check($check, '/[^\\s]/');
    }

    /**
     * Используется для сравнения 2 числовые значения.
     *
     * @param string|array $check1 if string is passed for a string must also be passed for $check2
     *    used as an array it must be passed as array('check1' => value, 'operator' => 'value', 'check2' -> value)
     * @param string $operator Can be either a word or operand
     *    is greater >, is less <, greater or equal >=
     *    less or equal <=, is less <, equal to ==, not equal !=
     * @param integer $check2 only needed if $check1 is a string
     * @return boolean Success
     */
    public static function comparison($check1, $operator = null, $check2 = null){
        if (is_array($check1)) {
            extract($check1, EXTR_OVERWRITE);
        }
        $operator = str_replace(array(' ', "\t", "\n", "\r", "\0", "\x0B"), '', strtolower($operator));

        switch ($operator) {
            case 'isgreater':
            case '>':
                if ($check1 > $check2) {
                    return true;
                }
                break;
            case 'isless':
            case '<':
                if ($check1 < $check2) {
                    return true;
                }
                break;
            case 'greaterorequal':
            case '>=':
                if ($check1 >= $check2) {
                    return true;
                }
                break;
            case 'lessorequal':
            case '<=':
                if ($check1 <= $check2) {
                    return true;
                }
                break;
            case 'equalto':
            case '==':
                if ($check1 == $check2) {
                    return true;
                }
                break;
            case 'notequal':
            case '!=':
                if ($check1 != $check2) {
                    return true;
                }
                break;
            default:
                self::$errors[] = 'You must define the $operator parameter for Validation::comparison()';
                break;
        }
        return false;
    }

    /**
     * Used when a custom regular expression is needed.
     *
     * @param string|array $check When used as a string, $regex must also be a valid regular expression.
     * 								As and array: array('check' => value, 'regex' => 'valid regular expression')
     * @param string $regex If $check is passed as a string, $regex must also be set to valid regular expression
     * @return boolean Success
     */
    public static function custom($check, $regex = null){
        if (is_array($check)) {
            extract(self::_defaults($check));
        }
        if ($regex === null) {
            self::$errors[] = 'You must define a regular expression for Validation::custom()';
            return false;
        }
        return self::_check($check, $regex);
    }

    /**
     * Проверка коректной даты года. 1901 - 2155
     * @param int $year
     * @return boolean Success
     */
    public static function year($year, $minYear = 1901, $maxYear = 2155){
        return (($year > $minYear) && ($year < $maxYear)) ? true : false;
    }

    /**
     * Date validation, determines if the string passed is a valid date.
     * keys that expect full month, day and year will validate leap years
     *
     * @param string $check a valid date string
     * @param string|array $format Use a string or an array of the keys below. Arrays should be passed as array('dmy', 'mdy', etc)
     * 	      Keys: dmy 27-12-2006 or 27-12-06 separators can be a space, period, dash, forward slash
     * 	            mdy 12-27-2006 or 12-27-06 separators can be a space, period, dash, forward slash
     * 	            ymd 2006-12-27 or 06-12-27 separators can be a space, period, dash, forward slash
     * 	            dMy 27 December 2006 or 27 Dec 2006
     * 	            Mdy December 27, 2006 or Dec 27, 2006 comma is optional
     * 	            My December 2006 or Dec 2006
     * 	            my 12/2006 separators can be a space, period, dash, forward slash
     * @param string $regex If a custom regular expression is used this is the only validation that will occur.
     * @return boolean Success
     */
    public static function date($check, $format = 'ymd', $regex = null){
        if (!is_null($regex)) {
            return self::_check($check, $regex);
        }

        $regex['dmy'] = '%^(?:(?:31(\\/|-|\\.|\\x20)(?:0?[13578]|1[02]))\\1|(?:(?:29|30)(\\/|-|\\.|\\x20)(?:0?[1,3-9]|1[0-2])\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:29(\\/|-|\\.|\\x20)0?2\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\\d|2[0-8])(\\/|-|\\.|\\x20)(?:(?:0?[1-9])|(?:1[0-2]))\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';
        $regex['mdy'] = '%^(?:(?:(?:0?[13578]|1[02])(\\/|-|\\.|\\x20)31)\\1|(?:(?:0?[13-9]|1[0-2])(\\/|-|\\.|\\x20)(?:29|30)\\2))(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$|^(?:0?2(\\/|-|\\.|\\x20)29\\3(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:(?:0?[1-9])|(?:1[0-2]))(\\/|-|\\.|\\x20)(?:0?[1-9]|1\\d|2[0-8])\\4(?:(?:1[6-9]|[2-9]\\d)?\\d{2})$%';
        $regex['ymd'] = '%^(?:(?:(?:(?:(?:1[6-9]|[2-9]\\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(\\/|-|\\.|\\x20)(?:0?2\\1(?:29)))|(?:(?:(?:1[6-9]|[2-9]\\d)?\\d{2})(\\/|-|\\.|\\x20)(?:(?:(?:0?[13578]|1[02])\\2(?:31))|(?:(?:0?[1,3-9]|1[0-2])\\2(29|30))|(?:(?:0?[1-9])|(?:1[0-2]))\\2(?:0?[1-9]|1\\d|2[0-8]))))$%';
        $regex['dMy'] = '/^((31(?!\\ (Feb(ruary)?|Apr(il)?|June?|(Sep(?=\\b|t)t?|Nov)(ember)?)))|((30|29)(?!\\ Feb(ruary)?))|(29(?=\\ Feb(ruary)?\\ (((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))|(0?[1-9])|1\\d|2[0-8])\\ (Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)\\ ((1[6-9]|[2-9]\\d)\\d{2})$/';
        $regex['Mdy'] = '/^(?:(((Jan(uary)?|Ma(r(ch)?|y)|Jul(y)?|Aug(ust)?|Oct(ober)?|Dec(ember)?)\\ 31)|((Jan(uary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep)(tember)?|(Nov|Dec)(ember)?)\\ (0?[1-9]|([12]\\d)|30))|(Feb(ruary)?\\ (0?[1-9]|1\\d|2[0-8]|(29(?=,?\\ ((1[6-9]|[2-9]\\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00)))))))\\,?\\ ((1[6-9]|[2-9]\\d)\\d{2}))$/';
        $regex['My']  = '%^(Jan(uary)?|Feb(ruary)?|Ma(r(ch)?|y)|Apr(il)?|Ju((ly?)|(ne?))|Aug(ust)?|Oct(ober)?|(Sep(?=\\b|t)t?|Nov|Dec)(ember)?)[ /]((1[6-9]|[2-9]\\d)\\d{2})$%';
        $regex['my']  = '%^(((0[123456789]|10|11|12)([- /.])(([1][9][0-9][0-9])|([2][0-9][0-9][0-9]))))$%';

        $format = (is_array($format)) ? array_values($format) : array($format);
        foreach ($format as $key) {
            if (self::_check($check, $regex[$key]) === true) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validates a datetime value
     * All values matching the "date" core validation rule, and the "time" one will be valid
     *
     * @param array $check Value to check
     * @param string|array $dateFormat Format of the date part
     * Use a string or an array of the keys below. Arrays should be passed as array('dmy', 'mdy', etc)
     * ## Keys:
     *
     * 	- dmy 27-12-2006 or 27-12-06 separators can be a space, period, dash, forward slash
     * 	- mdy 12-27-2006 or 12-27-06 separators can be a space, period, dash, forward slash
     * 	- ymd 2006-12-27 or 06-12-27 separators can be a space, period, dash, forward slash
     *  - dMy 27 December 2006 or 27 Dec 2006
     * 	- Mdy December 27, 2006 or Dec 27, 2006 comma is optional
     * 	- My December 2006 or Dec 2006
     * 	- my 12/2006 separators can be a space, period, dash, forward slash
     * @param string $regex Regex for the date part. If a custom regular expression is used this is the only validation that will occur.
     * @return boolean True if the value is valid, false otherwise
     * @see Validation::date
     * @see Validation::time
     */
    public static function datetime($check, $dateFormat = 'ymd', $regex = null){
        $valid = false;
        $parts = explode(' ', $check);
        if (!empty($parts) && count($parts) > 1) {
            $time  = array_pop($parts);
            $date  = implode(' ', $parts);
            $valid = self::date($date, $dateFormat, $regex) && self::time($time);
        }
        return $valid;
    }

    /**
     * Time validation, determines if the string passed is a valid time.
     * Validates time as 24hr (HH:MM) or am/pm ([H]H:MM[a|p]m)
     * Does not allow/validate seconds.
     *
     * @param string $check a valid time string
     * @return boolean Success
     */
    public static function time($check){
        return self::_check($check, '%^((0?[1-9]|1[012])(:[0-5]\d){0,2} ?([AP]M|[ap]m))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$%');
    }

    public static function realtime($check){
        $y = 1901;
        $m = $d = $h = $i = $s = 0;

        if (self::date($check)) {
            list($y, $m, $d) = explode("-", $check);
            $h = 23;
            $i = 59;
            $s = 59;
        } elseif (self::datetime($check)) {
            $parts = explode(' ', $check);
            list($y, $m, $d) = explode("-", $parts[0]);
            list($h, $i, $s) = explode(":", $parts[1]);
        }

        $time_old = mktime($h, $i, $s, $m, $d, $y);
        $time_now = explode(" ", microtime());

        $dt   = (int) (($time_old - $time_now[1]) / 60);

        if ($dt < -10)
            return false;

        return true;
    }

    /**
     * Boolean validation, determines if value passed is a boolean integer or true/false.
     *
     * @param string $check a valid boolean
     * @return boolean Success
     */
    public static function boolean($check){
        $booleanList = array(0, 1, '0', '1', true, false);
        return in_array($check, $booleanList, true);
    }

    /**
     * Checks that a value is a valid decimal. Both the sign and exponent are optional.
     *
     * Valid Places:
     *
     * - null => Any number of decimal places, including none. The '.' is not required.
     * - true => Any number of decimal places greater than 0, or a float|double. The '.' is required.
     * - 1..N => Exactly that many number of decimal places. The '.' is required.
     *
     * @param integer $check The value the test for decimal
     * @param integer $places
     * @param string $regex If a custom regular expression is used, this is the only validation that will occur.
     * @return boolean Success
     */
    public static function decimal($check, $places = null, $regex = null){
        if (is_null($regex)) {
            $lnum = '[0-9]+';
            $dnum = "[0-9]*[\.]{$lnum}";
            $sign = '[+-]?';
            $exp  = "(?:[eE]{$sign}{$lnum})?";

            if ($places === null) {
                $regex = "/^{$sign}(?:{$lnum}|{$dnum}){$exp}$/";
            } elseif ($places === true) {
                if (is_float($check) && floor($check) === $check) {
                    $check = sprintf("%.1f", $check);
                }
                $regex = "/^{$sign}{$dnum}{$exp}$/";
            } elseif (is_numeric($places)) {
                $places = '[0-9]{' . $places . '}';
                $dnum   = "(?:[0-9]*[\.]{$places}|{$lnum}[\.]{$places})";
                $regex  = "/^{$sign}{$dnum}{$exp}$/";
            }
        }
        return self::_check($check, $regex);
    }

    /**
     * Validates for an email address.
     *
     * Only uses getmxrr() checking for deep validation if PHP 5.3.0+ is used, or
     * any PHP version on a non-windows distribution
     *
     * @param string $check Value to check
     * @param boolean $deep Perform a deeper validation (if true), by also checking availability of host
     * @param string $regex Regex to use (if none it will use built in regex)
     * @return boolean Success
     */
    public static function email($check, $deep = false, $regex = null){
        if (is_array($check)) {
            extract(self::_defaults($check));
        }

        if (is_null($regex)) {
            $regex  = '/^[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . self::$_pattern['hostname'] . '$/i';
        }
        $return = self::_check($check, $regex);
        if ($deep === false || $deep === null) {
            return $return;
        }

        if ($return === true && preg_match('/@(' . self::$_pattern['hostname'] . ')$/i', $check, $regs)) {
            if (function_exists('getmxrr') && getmxrr($regs[1], $mxhosts)) {
                return true;
            }
            if (function_exists('checkdnsrr') && checkdnsrr($regs[1], 'MX')) {
                return true;
            }
            return is_array(gethostbynamel($regs[1]));
        }
        return false;
    }

    /**
     * Убедитесь, что значение ровно $comparedTo.
     *
     * @param mixed $check Value to check
     * @param mixed $comparedTo Value to compare
     * @return boolean Success
     */
    public static function equalTo($check, $comparedTo){
        return ($check === $comparedTo);
    }

    /**
     * Проверьте, что значение имеет действительное расширение файла.
     *
     * @param string|array $check Value to check
     * @param array $extensions file extensions to allow. By default extensions are 'gif', 'jpeg', 'png', 'jpg'
     * @return boolean Success
     */
    public static function extension($check, $extensions = array('gif', 'jpeg', 'png', 'jpg')){
        if (is_array($check)) {
            return self::extension(array_shift($check), $extensions);
        }
        $extension = strtolower(pathinfo($check, PATHINFO_EXTENSION));
        foreach ($extensions as $value) {
            if ($extension === strtolower($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validation of an IP address.
     *
     * @param string $check The string to test.
     * @param string $type The IP Protocol version to validate against
     * @return boolean Success
     */
    public static function ip($check, $type = 'both'){
        $type  = strtolower($type);
        $flags = 0;
        if ($type === 'ipv4') {
            $flags = FILTER_FLAG_IPV4;
        }
        if ($type === 'ipv6') {
            $flags = FILTER_FLAG_IPV6;
        }
        return (boolean) filter_var($check, FILTER_VALIDATE_IP, array('flags' => $flags));
    }

    /**
     * Проверяет, является ли длина строки больше или равна минимальной длины.
     *
     * @param string $check The string to test
     * @param integer $min The minimal string length
     * @return boolean Success
     */
    public static function minLength($check, $min){
        return mb_strlen($check) >= $min;
    }

    /**
     * Проверяет, является ли длина строки меньше или равна максимальной длине.
     *
     * @param string $check The string to test
     * @param integer $max The maximal string length
     * @return boolean Success
     */
    public static function maxLength($check, $max){
        return mb_strlen($check) <= $max;
    }

    /**
     * Подтвердить множественного выбора.
     *
     * Valid Options
     *
     * - in => предоставить список вариантов, которые выборов должны быть изготовлены из
     * - max => maximum number of non-zero choices that can be made
     * - min => minimum number of non-zero choices that can be made
     *
     * @param array $check Value to check
     * @param array $options Options for the check.
     * @param boolean $strict Defaults to true, set to false to disable strict type check
     * @return boolean Success
     */
    public static function multiple($check, $options = array(), $strict = true){
        $defaults = array('in'     => null, 'max'    => null, 'min'    => null);
        $options = array_merge($defaults, $options);
        $check   = array_filter((array) $check);
        if (empty($check)) {
            return false;
        }
        if ($options['max'] && count($check) > $options['max']) {
            return false;
        }
        if ($options['min'] && count($check) < $options['min']) {
            return false;
        }
        if ($options['in'] && is_array($options['in'])) {
            foreach ($check as $val) {
                if (!in_array($val, $options['in'], $strict)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Checks if a value is numeric.
     *
     * @param string $check Value to check
     * @return boolean Success
     */
    public static function numeric($check){
        return is_numeric($check);
    }

    public static function integer($check) {
        return is_integer($check);
    }

    public static function float($check) {
       return is_float($check);
    }

    /**
     * Checks if a value is a natural number.
     *
     * @param string $check Value to check
     * @param boolean $allowZero Set true to allow zero, defaults to false
     * @return boolean Success
     * @see http://en.wikipedia.org/wiki/Natural_number
     */
    public static function naturalNumber($check, $allowZero = false){
        $regex = $allowZero ? '/^(?:0|[1-9][0-9]*)$/' : '/^[1-9][0-9]*$/';
        return self::_check($check, $regex);
    }

    /**
     * Убедиться, что номер находится в указанном диапазоне..
     * if $lower and $upper are not set, will return true if
     * $check is a legal finite on this platform
     *
     * @param string $check Value to check
     * @param integer $lower Lower limit
     * @param integer $upper Upper limit
     * @return boolean Success
     */
    public static function range($check, $lower = null, $upper = null){
        if (!is_numeric($check)) {
            return false;
        }
        if (isset($lower) && isset($upper)) {
            return ($check > $lower && $check < $upper);
        }
        return is_finite($check);
    }

    /**
     * Checks that a value is a valid URL according to http://www.w3.org/Addressing/URL/url-spec.txt
     *
     * The regex checks for the following component parts:
     *
     * - a valid, optional, scheme
     * - a valid ip address OR
     *   a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt
     *   with an optional port number
     * - an optional valid path
     * - an optional query string (get parameters)
     * - an optional fragment (anchor tag)
     *
     * @param string $check Value to check
     * @param boolean $strict Require URL to be prefixed by a valid scheme (one of http(s)/ftp(s)/file/news/gopher)
     * @return boolean Success
     */
    public static function url($check, $strict = false){
        self::_populateIp();
        $validChars = '([' . preg_quote('!"$&\'()*+,-.@_:;=~[]') . '\/0-9a-z\p{L}\p{N}]|(%[0-9a-f]{2}))';
        $regex      = '/^(?:(?:https?|ftps?|sftp|file|news|gopher):\/\/)' . (!empty($strict)
                            ? '' : '?') .
                '(?:' . self::$_pattern['IPv4'] . '|\[' . self::$_pattern['IPv6'] . '\]|' . self::$_pattern['hostname'] . ')(?::[1-9][0-9]{0,4})?' .
                '(?:\/?|\/' . $validChars . '*)?' .
                '(?:\?' . $validChars . '*)?' .
                '(?:#' . $validChars . '*)?$/iu';
        return self::_check($check, $regex);
    }

    /**
     * Проверяет, является ли значение в данном списке.
     *
     * @param string $check Value to check
     * @param array $list List to check against
     * @param boolean $strict Defaults to true, set to false to disable strict type check
     * @return boolean Success
     */
    public static function inList($check, $list, $strict = true){
        return in_array($check, $list, $strict);
    }

    /**
     * Runs an user-defined validation.
     *
     * @param string|array $check value that will be validated in user-defined methods.
     * @param object $object class that holds validation method
     * @param string $method class method name for validation to run
     * @param array $args arguments to send to method
     * @return mixed user-defined class class method returns
     */
    public static function userDefined($check, $object, $method, $args = null){
        return call_user_func_array(array($object, $method), array($check, $args));
    }

    /**
     * Checks that a value is a valid uuid - http://tools.ietf.org/html/rfc4122
     *
     * @param string $check Value to check
     * @return boolean Success
     */
    public static function uuid($check){
        $regex = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i';
        return self::_check($check, $regex);
    }

    /**
     * Attempts to pass unhandled Validation locales to a class starting with $classPrefix
     * and ending with Validation.  For example $classPrefix = 'nl', the class would be
     * `NlValidation`.
     *
     * @param string $method The method to call on the other class.
     * @param mixed $check The value to check or an array of parameters for the method to be called.
     * @param string $classPrefix The prefix for the class to do the validation.
     * @return mixed Return of Passed method, false on failure
     */
    protected static function _pass($method, $check, $classPrefix){
        $className = ucwords($classPrefix) . 'Validation';
        if (!class_exists($className)) {
            trigger_error(__d('cake_dev', 'Could not find %s class, unable to complete validation.', $className), E_USER_WARNING);
            return false;
        }
        if (!method_exists($className, $method)) {
            trigger_error(__d('cake_dev', 'Method %s does not exist on %s unable to complete validation.', $method, $className), E_USER_WARNING);
            return false;
        }
        $check = (array) $check;
        return call_user_func_array(array($className, $method), $check);
    }

    /**
     * Runs a regular expression match.
     *
     * @param string $check Value to check against the $regex expression
     * @param string $regex Regular expression
     * @return boolean Success of match
     */
    protected static function _check($check, $regex){
        if (is_string($regex) && preg_match($regex, $check)) {
            self::$errors[] = false;
            return true;
        } else {
            self::$errors[] = true;
            return false;
        }
    }

    /**
     * Get the values to use when value sent to validation method is
     * an array.
     *
     * @param array $params Parameters sent to validation method
     * @return void
     */
    protected static function _defaults($params){
        self::_reset();
        $defaults = array(
            'check'   => null,
            'regex'   => null,
            'country' => null,
            'deep'    => false,
            'type'    => null
        );
        $params   = array_merge($defaults, $params);
        if ($params['country'] !== null) {
            $params['country'] = mb_strtolower($params['country']);
        }
        return $params;
    }

    /**
     * Luhn algorithm
     *
     * @param string|array $check
     * @param boolean $deep
     * @return boolean Success
     * @see http://en.wikipedia.org/wiki/Luhn_algorithm
     */
    public static function luhn($check, $deep = false){
        if (is_array($check)) {
            extract(self::_defaults($check));
        }
        if ($deep !== true) {
            return true;
        }
        if ($check == 0) {
            return false;
        }
        $sum    = 0;
        $length = strlen($check);

        for ($position = 1 - ($length % 2); $position < $length; $position += 2) {
            $sum += $check[$position];
        }

        for ($position = ($length % 2); $position < $length; $position += 2) {
            $number = $check[$position] * 2;
            $sum += ($number < 10) ? $number : $number - 9;
        }

        return ($sum % 10 == 0);
    }


    /**
     * Checking for upload errors
     *
     * @param string|array $check
     * @retrun boolean
     * @see http://www.php.net/manual/en/features.file-upload.errors.php
     */
    public static function uploadError($check){
        if (is_array($check) && isset($check['error'])) {
            $check = $check['error'];
        }

        return $check === UPLOAD_ERR_OK;
    }

    /**
     * Lazily populate the IP address patterns used for validations
     *
     * @return void
     */
    protected static function _populateIp(){
        if (!isset(self::$_pattern['IPv6'])) {
            $pattern = '((([0-9A-Fa-f]{1,4}:){7}(([0-9A-Fa-f]{1,4})|:))|(([0-9A-Fa-f]{1,4}:){6}';
            $pattern .= '(:|((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})';
            $pattern .= '|(:[0-9A-Fa-f]{1,4})))|(([0-9A-Fa-f]{1,4}:){5}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})';
            $pattern .= '(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)';
            $pattern .= '{4}(:[0-9A-Fa-f]{1,4}){0,1}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
            $pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){3}(:[0-9A-Fa-f]{1,4}){0,2}';
            $pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|';
            $pattern .= '((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:){2}(:[0-9A-Fa-f]{1,4}){0,3}';
            $pattern .= '((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2}))';
            $pattern .= '{3})?)|((:[0-9A-Fa-f]{1,4}){1,2})))|(([0-9A-Fa-f]{1,4}:)(:[0-9A-Fa-f]{1,4})';
            $pattern .= '{0,4}((:((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)';
            $pattern .= '|((:[0-9A-Fa-f]{1,4}){1,2})))|(:(:[0-9A-Fa-f]{1,4}){0,5}((:((25[0-5]|2[0-4]';
            $pattern .= '\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})?)|((:[0-9A-Fa-f]{1,4})';
            $pattern .= '{1,2})))|(((25[0-5]|2[0-4]\d|[01]?\d{1,2})(\.(25[0-5]|2[0-4]\d|[01]?\d{1,2})){3})))(%.+)?';

            self::$_pattern['IPv6'] = $pattern;
        }
        if (!isset(self::$_pattern['IPv4'])) {
            $pattern = '(?:(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])\.){3}(?:25[0-5]|2[0-4][0-9]|(?:(?:1[0-9])?|[1-9]?)[0-9])';
            self::$_pattern['IPv4'] = $pattern;
        }
    }

    /**
     * Reset internal variables for another validation run.
     *
     * @return void
     */
    protected static function _reset(){
        self::$errors = array();
    }

}
