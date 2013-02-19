<?php
/**
 * ACUtils class  - ACUtils.php file
 *
 * @author      Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, AC
 */

/**
 * ACUtils
 *
 * @package		AC
 */
class ACUtils {

    /**
     * Трасировка в латинские символы.
     *
     * @param string $str
     * @return string
     */
    public static function letterTrans($str) {
        $rus = "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ";
        $eng = "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE";
        $str = strtr($str, $rus, $eng);

        return strtr($str, array(
            'е' => "yo", 'х' => "h", 'ц' => "ts", 'ч' => "ch", 'ш' => "sh",
            'щ' => "shch", 'ъ' => '', 'ь' => '', 'ю' => "yu", 'я' => "ya",
            'Е' => "Yo", 'Х' => "H", 'Ц' => "Ts", 'Ч' => "Ch", 'Ш' => "Sh",
            'Щ' => "Shch", 'Ъ' => '', 'Ь' => '', 'Ю' => "Yu", 'Я' => "Ya"
                ));
    }

    /**
     * Преобразует специальные символы в HTML сущности
     *
     * @param array|string $data
     * @return mixed
     */
    public static function htmlspecialchars(&$data, $quote_style = 2) {
        if (!is_array($data)) {
            $data = htmlspecialchars($data, $quote_style);
            return $data;
        }

        reset($data);
        while (list($key, $value) = each($data)) {
            if (is_array($value))
                $data[$key] = self::htmlspecialchars($value, $quote_style);
            else
                $data[$key] = htmlspecialchars($value, $quote_style);
        }

        return $data;
    }

    /**
     * Преобразовывает символы строки в другую кодировку.
     *
     * @param string $data
     * @param string $in_charset начальная кодировка
     * @param string $out_charset конечная кодировка
     * @return string
     */
    public static function iconv(&$data, $in_charset = 'UTF-8', $out_charset = 'windows-1251') {
        if (!is_array($data) && !is_object($data)) {
            $data = iconv($in_charset, $out_charset, $data);
            return $data;
        }

        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                if (is_object($data)) {
                    $data->$key = self::iconv($value, $in_charset, $out_charset);
                } else {
                    $data[$key] = self::iconv($value, $in_charset, $out_charset);
                }
            } else {
                if (is_object($data)) {
                    $data->$key = iconv($in_charset, $out_charset, $value);
                } else {
                    $data[$key] = iconv($in_charset, $out_charset, $value);
                }
            }
        }

        return $data;
    }

    public static function trim(&$data) {
        if (!is_array($data) && !is_object($data)) {
            $data = trim($data);
            return $data;
        }
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                if (is_object($data)) {
                    $data->$key = self::trim($value);
                } else {
                    $data[$key] = self::trim($value);
                }
            } else {
                if (is_object($data)) {
                    $data->$key = trim($value);
                } else {
                    $data[$key] = trim($value);
                }
            }
        }
        return $data;
    }

    public static function userDefined(&$data, $callback) {
        if (!is_array($data) && !is_object($data)) {
            $data = call_user_func_array($callback, array($data));
            return $data;
        }

        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = self::userDefined($value, $callback);
            } else {
                $value = call_user_func_array($callback, array($value));
            }
            if (is_object($data)) {
                $data->$key = $value;
            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    /**
     * Время работы скрипта.
     * @param float   $start -  стартовое время
     * @return float  пройденое время
     */
    public static function getExecutionTime() {
        if (defined('TIME_START'))
            return microtime(true) - TIME_START;
        else
            return microtime(true) - $_SERVER["REQUEST_TIME"];
    }

    /**
     * Объем занимаемой памяти в байтах
     *
     * @return integer объем занимаемой памяти
     */
    public static function getMemoryUsage() {
        if (function_exists('memory_get_usage'))
            return memory_get_usage();
        else {
            $output = array();
            if (strncmp(PHP_OS, 'WIN', 3) === 0) {
                exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);
                return isset($output[5]) ? preg_replace('/[\D]/', '', $output[5]) * 1024 : 0;
            } else {
                $pid    = getmypid();
                exec("ps -eo%mem,rss,pid | grep $pid", $output);
                $output = explode("  ", $output[0]);
                return isset($output[1]) ? $output[1] * 1024 : 0;
            }
        }
    }

    /**
     * IP клиента
     * @return string
     */
    public static function GetIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Перенаправляет на страницу. Автоматически добовляет параметр r=XX произвольного значения,
     * вне зависимости имееться он или нет. При отсутствии целевой страници page,
     * переадрисуеться на туже страницу с теме же параметрами с изменеыми на params_arr,
     * если таковы имеються.
     *
     * @param string $page целевая страница
     * @param array $data параметры get
     */
    public static function location($page = "", $data = false) {
        if (!is_array($data)) {
            $data = array();
        }

        if ($page == "") {
            $page = substr($_SERVER["SCRIPT_NAME"], 1);
            $url  = $_SERVER["QUERY_STRING"];

            $key_value = explode("&", $url);
            foreach ($key_value as $value) {
                $kv = explode("=", $value);
                if ($kv[0] && $kv[1]) {
                    if (!$data[$kv[0]]) {
                        $data[$kv[0]] = $kv[1];
                    }
                }
            }
        }
        if ($page == "/")
            $page         = "";

        unset($data["r"]);
        $data["r"] = rand();

        $get = "";

        foreach ($data as $key => $value) {
            $get .= $key . "=" . $value . "&";
        }

        $get    = substr($get, 0, -1);
        $header = "Location: http://" . $_SERVER["HTTP_HOST"] . "/{$page}?{$get}";
        header($header);
        exit();
    }

    /**
     * Парсирует строку URL
     * @param string $url
     * @param array $options
     * @return type
     */
    public static function parseURL($url, array $options = array()) {
        if (isset($options['first']))
            $first = $options['first'];
        if (isset($options['last']))
            $last  = $options['last'];

        $trim  = (isset($options['trim'])) ? true : false;
        $dr    = (isset($options['dr'])) ? $options['dr'] : DIRECTORY_SEPARATOR;

        $pattern     = array(
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
     * декодирует URL-кодированную строку
     *
     * @param array|string $data
     * @return mixed
     */
    public static function URLDecode($data) {
        if (!is_array($data)) {
            $data = urldecode($data);
            return $data;
        }
        reset($data);
        while (list($key, $value) = each($data)) {
            if (is_array($value)) {
                $data[$key] = self::URLDecode($value);
            } else {
                $data[$key] = urldecode($value);
            }
        }
        return $data;
    }

    /**
     * Парсирует шаблон из файла. Замеяет переменные в шаблоне.
     *
     * @param string $file имя шаблона
     * @param array $vars масив переменных вставляемые в шаблон
     * @return string преобразовынный шаблон
     */
    public static function parseTemplateFile($file, $vars = false) {
        $string = @file_get_contents($file);

        if ($string) {
            $string = self::parseTemplateString($string, $vars);
        } //return

        return $string;
    }

    /**
     * Парсирует шаблон строки. Замеяет переменные в шаблоне.
     *
     * @param string $string строка
     * @param array $vars масив переменных вставляемые в шаблон
     * @return string преобразовынный шаблон
     * @version	0.2
     */
    public static function parseTemplateString($string, $vars = false) {
        if (preg_match_all("/{([A-Z]+[\d_A-Z]*)}/", $string, $regs)) {
            $regs = $regs[1];
            foreach ($regs as $key) {
                $key        = strtolower($key);
                $vars[$key] = $vars[$key];
            }
        }

        if (is_array($vars)) {
            reset($vars);
            while (list($key, $value) = each($vars)) {
                $string = str_replace("{" . strtoupper($key) . "}", $value, $string);
            }
        }

        return $string;
    }

    /**
     * Функция генерирует пароль
     *
     * @param int $number количество символов в пароле.
     * @return string
     */
    public static function randString($number = 10) {
        $chr = "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE1234567890";
        $pass = "";
        for ($i = 0; $i < $number; $i++) {
            $index = rand(0, strlen($chr));
            $pass .= substr($chr, $index, 1);
        }
        return $pass;
    }

    /**
     * Объем памяти в единицах измерения
     * @param integer $size
     * @return string
     */
    public static function getMemoryString($size) {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $str  = @round($size / pow(1024, ($i    = floor(Log($size, 1024)))), 2) . ' ' . $unit[$i];
        return $str;
    }

}
