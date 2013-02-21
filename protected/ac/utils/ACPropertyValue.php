<?php
/**
 * ACPropertyValue class  - ACPropertyValue.php file
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * ACEnsure class
 *
 * @package		AC
 */
class ACPropertyValue {

    /**
     * Обеспечивает правельный URL
     * $options = array(
     * - first => 0
     * - last  => 0
     * - trim  => 0
     * - dr    => "/"
     * )
     *
     * @param string $url
     * @param array $options
     * @return type
     */
    public static function ensureURL($url, array $options = array()) {
        if (isset($options['first']))
            $first = $options['first'];
        if (isset($options['last']))
            $last  = $options['last'];
        $trim  = (isset($options['trim'])) ? true : false;
        $dr    = (isset($options['dr'])) ? $options['dr'] : DIRECTORY_SEPARATOR;

        $pattern = array(
            "#[\\\/]+#",
            "#^[\\\/]+#",
            "#[\\\/]+$#"
        );
        $replacement = array($dr);
        if ($trim) {
            $replacement[] = (($first) ? $dr : '');
            $replacement[] = (($last) ? $dr : '');
        }

        $url = preg_replace($pattern, $replacement, $url);
        return $url;
    }

    /**
     * Преобразует поля анкет (строка или масив) в масив (список), значения
     * которого являються имя полей.
     * @param string|array $value   - поля анкет
     * @param string $default - значение по умолчание в случае пустого ответа
     * @return array
     */
    public static function ensureFields($value, $default = null) {
        $delimiter = ",";
        $value     = (!is_array($value)) ? explode($delimiter, $value) : $value;
        foreach ($value as $key => $val) {
            $val   = trim($val); // убрезаем пробелы
            if (!$val)
                unset($value[$key]);
        }
        $value = array_unique($value);

        if (count($value) == 0 && $default !== null) {
            $value = array($default);
        }

        return $value;
    }

    /**
     * Преобразует список id, переданные масивом или строкой в масив.
     * Возвращает только целые числа без повторений.
     * В случае отсутствия значений возвращает <b>$default</b> по умолчанию -1.
     *
     * @param string|array $value
     * @param string $default
     * @return array
     */
    public static function ensureNumbers($value, $default = -1) {
        $delimiter = ",";
        $value     = (!is_array($value)) ? explode($delimiter, $value) : $value;
        foreach ($value as $key => $val) {
            $val = trim($val);
            if (!$val) {
                unset($value[$key]);
            }
        }
        if (count($value) == 0 && $default !== null) {
            $value = array($default);
        }
        array_walk($value, function(&$val) {
                    $val   = (integer) $val;
                });
        $value = array_unique($value);
        return $value;
    }

    /**
     * Преобразует значение в логический тип.
     * Обратите внимание, что 'true' строка (без учета регистра) будут преобразованы в true,
     * Строка false' (без учета регистра) будут преобразованы в false.
     * Если строка представляет собой ненулевое число, то оно будет рассматриваться как истинное.
     *
     * @param mixed $value
     * @return boolean
     */
    public static function ensureBoolean($value) {
        if (is_string($value))
            return !strcasecmp($value, 'true') || $value != 0;
        else
            return (boolean) $value;
    }

    /**
     * Converts a value to string type.
     * Note, a boolean value will be converted to 'true' if it is true
     * and 'false' if it is false.
     * @param mixed $value the value to be converted.
     * @return string
     */
    public static function ensureString($value) {
        if (is_bool($value))
            return (($value) ? 'true' : 'false');
        else
            return (string) $value;
    }

    /**
     * Converts a value to integer type.
     * @param mixed $value the value to be converted.
     * @return integer
     */
    public static function ensureInteger($value) {
        return (integer) $value;
    }

    /**
     * Преобразование в целое,положительное число либо 0
     * @param mixed $value
     * @return int
     */
    public static function ensurePositive($value) {
        $value = intval($value);
        return (($value < 0) ? 0 : $value);
    }

    /**
     * Converts a value to float type.
     * @param mixed $value the value to be converted.
     * @return float
     */
    public static function ensureFloat($value) {
        return (float) $value;
    }

    /**
     * Converts a value to array type. I
     * @param mixed $value the value to be converted.
     * @return array
     */
    public static function ensureArray($value) {
        if (empty($value)) {
            $value = array();
        }
        if (!is_array($value)) {
            $value = array((string) $value);
        }
        return $value;
    }

    /**
     * Converts a value to object type.
     * @param mixed $value the value to be converted.
     * @return object
     */
    public static function ensureObject($value) {
        return (object) $value;
    }

    /**
     * Парсирует значение в строковый формат ГГГГ-ММ-ДД или масив [year, month, day]
     *
     * @param mixed   $value  - масив или строка.
     * @parem boolean $to_str - в случае true результат преобразуеться в строку
     * @return string|array
     */
    public static function ensureDate($value, $to_str = true) {
        $date = array("0000", "00", "00");

        if (!is_array($value)) {
            if (ACValidation::datetime($value)) {
                list($value, $d) = explode(" ", $value);
            }
            $value = explode("-", $value);
        }

        $date[0] = ACPropertyValue::ensurePositive($value[0]);
        if (!ACValidation::year($date[0]))
            $date[0] = "0000";

        $date[1] = ACPropertyValue::ensurePositive($value[1]);
        if ($date[1] <= 9)
            $date[1] = "0" . $date[1];

        $date[2] = ACPropertyValue::ensurePositive($value[2]);
        if ($date[2] <= 9)
            $date[2] = "0" . $date[2];

        if (!checkdate($date[1], $date[2], $date[0])) {
            $date = array("0000", "00", "00");
        }

        if ($to_str)
            $date = implode("-", $date);

        return $date;
    }

    /**
     * 2012-11-11 11:15:11
     * 11:15:11
     * array(2012,11,11,11,15,11)
     * array(11,15,11)
     *
     * @param string|array $value
     * @param type $to_str
     * @return array|string
     */
    public static function ensureTime($value, $to_str = true) {
        $time = array("00", "00", "00");

        if (!is_array($value)) {
            if (ACValidation::datetime($value)) {
                list($d, $value) = explode(" ", $value);
            }
            $value = explode(":", $value);
        }

        $time[0] = ACPropertyValue::ensurePositive($value[0]);
        if ($time[0] <= 9)
            $time[0] = "0" . $time[0];
        if ($time[0] > 23)
            $time[0] = "00";

        $time[1] = ACPropertyValue::ensurePositive($value[1]);
        if ($time[1] <= 9)
            $time[1] = "0" . $time[1];
        if ($time[1] > 59)
            $time[1] = "00";

        $time[2] = ACPropertyValue::ensurePositive($value[2]);
        if ($time[2] <= 9)
            $time[2] = "0" . $time[2];
        if ($time[2] > 59)
            $time[2] = "00";

        if ($to_str)
            $time = implode(":", $time);

        return $time;
    }

    public static function ensureDatetime($value, $to_str = true) {
        $datetime = array("0000-00-00 00:00:00");

        if (is_array($value)) {
            $time = array_slice($value, 3);
            $date = array_slice($value, 0, 3);
        } else {
            list($date, $time) = explode(" ", $value);
            $date = explode("-", $date);
            $time = explode(":", $time);
        }

        $date = self::ensureDate($date, $to_str);
        $time = self::ensureTime($time, $to_str);

        if ($to_str)
            $datetime = $date . " " . $time;
        else
            $datetime = $date + $time;

        return $datetime;
    }

    /**
     * Обеспечивает значение по умолчанию в случии не валидности параметра.
     * Если метод проверки валидности требует дополнительных параметров, их тоже
     * необходимо передать в виде масива $method($name, $arg1, $arg2).
     * Метод должен возвращать тип boolean
     *  true - в случае валидности
     *
     * @param mixed         $value   - проверяемое значение
     * @param array|string  $method  - имя методо проверки
     * @param mixed         $default - значение по умолчанию
     * @return mixed
     */
    public static function validDefault($value, $method, $default = null) {
        $args = array($value);
        if (is_array($method)) {
            $args   = $method;
            $method = array_shift($args);
            array_unshift($args, $value);
        }
        if (!call_user_func_array(array('ACValidation', $method), $args)) {
            $value = $default;
        }
        return $value;
    }

}