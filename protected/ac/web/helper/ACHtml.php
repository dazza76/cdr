<?php

/**
 * Html class  - Html.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright   (c) 2013, CMRI
 */

/**
 * Html class
 *
 * @package		AC
 */
class ACHtml {

    /**
     * Кодирует специальные символы в HTML сущности.
     * @param string $text data to be encoded
     * @return string the encoded data
     */
    public static function encode($text) {
        return htmlspecialchars($text, ENT_QUOTES);
    }

    /**
     * Декодирует специальные HTML-сущности обратно в соответствующие символы.
     * @param string $text data to be decoded
     * @return string the decoded data
     */
    public static function decode($text) {
        return htmlspecialchars_decode($text, ENT_QUOTES);
    }

    /**
     * Переоразует масив в строку формата (ключ=заченние) арументов со значениями для тегов.
     *  - Элементы с числовым ключем игнорируються
     *  - Элементы с пустым значением игнорируються
     * @param array $attr
     * @return string
     */
    public static function attr($attr) {
        $html = '';
        if (is_array($attr) || is_object($attr)) {
            foreach ($attr as $name => $value) {
                if (!is_numeric($name) && $value)
                    $html .= ' ' . $name . '="' . self::encode($value) . '"';
            }
        } elseif (is_string($attr)) {
            $html = $attr;
        }
        return $html;
    }

    /**
     * Возвращает строку элемента input type=""
     * @param string         $type
     * @param string         $name
     * @param string|array   $attr
     * @return string
     */
    public static function input($type, $name = null, $attr = null) {
        if ($name) {
            $name = 'name="' . self::encode($name) . '"';
        }
        $attr = $name . " " . self::attr($attr);
        return '<input type="' . self::encode($type) . '" ' . $attr . ' />';
    }

    /**
     *
     * @param array          $opt
     * @param string         $name
     * @param array          $attr
     * @param string|int     $def  - значение по умолчанию
     * @return string
     */
    public static function select($opt, $name = null, $attr = null, $def = null) {
        if ($name) {
            $name = 'name="' . self::encode($name) . '"';
        }
        if (!is_array($def)) {
            $def = array($def);
        }

        $attr = $name . " " . self::attr($attr);

        $html = '<select ' . $attr . '>';
        foreach ($opt as $value => $text) {
            $sel = (in_array($value, $def)) ? 'selected="selected"' : "";
            $html .= '<option value="' . self::encode($value) . '" ' . $sel . '>'
                    . self::encode($text)
                    . "</option>";
        }
        $html .= "</select>";

        return $html;
    }

    /**
     * Месяца.
     * именительный – nom, родительный – gen,
     */
    public static function month($month, $nom = false) {
        $m      = ($nom) ? "mon" : "gen";
        $months = array(
            "nom" => array(
                "1"  => "январь",
                "2"  => "февраль",
                "3"  => "март",
                "4"  => "апрель",
                "5"  => "май",
                "6"  => "июнь",
                "7"  => "июль",
                "8"  => "август",
                "9"  => "сентябрь",
                "10" => "октябрь",
                "11" => "ноябрь",
                "12" => "декабрь"
            ),
            "gen" => array(
                "1"  => "января",
                "2"  => "февраля",
                "3"  => "марта",
                "4"  => "апреля",
                "5"  => "мая",
                "6"  => "июня",
                "7"  => "июля",
                "8"  => "августа",
                "9"  => "сентября",
                "10" => "октября",
                "11" => "ноября",
                "12" => "декабря"
            )
        );
        $str    = $months[$m][$month];

        return $str;
    }

}

